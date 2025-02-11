<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\ApiManager;
use App\Model\KurohonPurchaseInformation;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CsvController extends Controller
{

    public function kurohonListDownload()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $job = $this->findKurohonPurchaseInfo();
        $csvHeader = ['ID', 'ユーザーID', '名前', 'カナ', '郵便番号', '都道府県', '市区町村', '番地', '電話番号', 'メールアドレス', '登録日', '更新日', '削除日'];
        array_unshift($job, $csvHeader);

        $stream = fopen('php://temp', 'r+b');

        foreach ($job as $value) {
            fputcsv($stream, $value);
        }

        rewind($stream);
        $filename = "kurohonListWithId_" . date('YmdHis') . ".csv";

        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS', 'UTF-8');
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',

        ];

        return \Response::make($csv, 200, $headers);
    }

    private function findKurohonPurchaseInfo()
    {
        $result = [];
        $findValue = KurohonPurchaseInformation::all()->toArray();
        foreach ($findValue as $value) {
            $prefectureInfo = MasterAddr1Mst::find($value['prefecture']);
            $value['prefecture'] = $prefectureInfo->addr1;

            $cityInfo = MasterAddr2Mst::find($value['city']);
            $value['city'] = $cityInfo->addr2;
            $result[] = $value;
        }

        return $result;
    }

    public function kurohonListUpload(Request $request)
    {
        // ログインチェック
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['message'] = "";

        // validate処理
        $validator = $this->validateUploadFile($request);
        // validateエラーの場合メッセージを返す
        if ($validator->fails() === true) {
            $data['message'] = $validator->errors()->first('csv_file');

            return view('admin.kurohonOperation', $data);
        }

        // CSVファイルをサーバーに一時保存
        $temporary_csv_file = $request->file('csv_file')->store('csv');
        $fp = fopen(storage_path('app/') . $temporary_csv_file, 'r');

        // カラム数チェック
        $headers = fgetcsv($fp);
        if (count($headers) != 8) {
            fclose($fp);
            \Storage::delete($temporary_csv_file);
            $data['message'] = '黒本リストCSVの登録に失敗しました。CSVファイルのフォーマットが正しいことを確認してださい。';

            return view('admin.kurohonOperation', $data);
        }

        // 登録処理
        $rowCount = 0;
        $registCount = 0;
        $errorRow = [];
        while ($row = fgetcsv($fp)) {
            $rowCount++;
            mb_convert_variables('UTF-8', 'SJIS', $row);
            $validateResult = $this->validateKurohonRegistData($row);
            if (!empty($validateResult)) {
                $errorRow[] = "【登録エラー】{$rowCount}行目：{$validateResult}";
                continue;
            }
            $registData = KurohonPurchaseInformation::where('mail', $row[7])->first();
            if (empty($registData)) {
                $registData = new KurohonPurchaseInformation;
                $registData->user_id = uniqid();
                $registData->mail = $row[7];
            }
            $registData->name_kan = $row[0];
            $registData->name_cana = $row[1];
            $registData->zip_code = $row[2];
            $prefectureInfo = MasterAddr1Mst::where('addr1', $row[3])->first();
            $registData->prefecture = $prefectureInfo->id;
            $cityInfo = MasterAddr2Mst::where('addr1_id', $prefectureInfo->id)->where('addr2', $row[4])->first();
            $registData->city = $cityInfo->id;
            $registData->house_number = $row[5];
            $registData->tel = $row[6];

            DB::beginTransaction();
            try {
                $registData->save();
                $registCount++;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $errorRow[] = "【登録エラー】{$rowCount}行目：データ不正";
            }
        }

        // 一時ファイルを削除
        fclose($fp);
        \Storage::delete($temporary_csv_file);
        // 完了メッセージを表示
        $errorCount = count($errorRow);
        $data['message'] = $data['message'] . "黒本リストCSVの登録が完了しました。<br>成功件数：{$registCount}<br>エラー件数：{$errorCount}<br>";
        foreach ($errorRow as $value) {
            $data['message'] = $data['message'] . "<br>{$value}";
        }

        return view('admin.kurohonOperation', $data);
    }

    /**
     * CSVデータを基本型に基づいたJSONデータに変換(回答番号のみ)
     *
     * @param Request $request
     * @return void
     */
    public function downloadAnswerNoJson(Request $request)
    {
        return $this->processCsvFile($request, 'answer_no.json', 'answerNoConvertToFormattedJson');
    }

    /**
     * CSVデータを基本型に基づいたJSONデータに変換(回答)
     *
     * @param Request $request
     * @return void
     */
    public function downloadAnswerJson(Request $request)
    {
        return $this->processCsvFile($request, 'answer_all.json', 'answerConvertToFormattedJson');
    }

    private function validateKurohonRegistData($registData): string
    {
        $result = '';
        $error = [];
        // 名前
        if (empty($registData[0])) {
            $error[] = "「名前」未入力";
        } else {
            // 桁数チェック
            if (!$this->checkDiditLessThan($registData[0], 64)) {
                $error[] = "「名前」桁数不正";
            }
        }
        // カナ
        if (empty($registData[1])) {
            $error[] = "「カナ」未入力";
        } else {
            // 桁数チェック
            if (!$this->checkDiditLessThan($registData[1], 64)) {
                $error[] = "「カナ」桁数不正";
            }
        }
        // 郵便番号
        if (empty($registData[2])) {
            $error[] = "「郵便番号」未入力";
        } else {
            if (!$this->checkDiditEqual($registData[2], 7)) {
                $error[] = "「郵便番号」桁数不正";
            }
        }
        // 都道府県
        $prefectureInfo = MasterAddr1Mst::where('addr1', $registData[3])->first();
        if (empty($prefectureInfo)) {
            $error[] = "「都道府県」不正または未入力";
        } else {
            // 市区町村
            $cityInfo = MasterAddr2Mst::where('addr1_id', $prefectureInfo->id)->where('addr2', $registData[4])->first();
            if (empty($cityInfo)) {
                $error[] = "「市区町村」不正または未入力";
            }
        }
        // カナ必須チェック
        if (empty($registData[5])) {
            $error[] = "「番地」未入力";
        } else {
            // 桁数チェック
            if (!$this->checkDiditLessThan($registData[5], 255)) {
                $error[] = "「番地」桁数不正";
            }
        }
        // 電話番号
        if (empty($registData[6])) {
            $error[] = "「電話番号」未入力";
        } else {
            // 桁数チェック
            if (!$this->checkDiditLessThan($registData[6], 20)) {
                $error[] = "「電話番号」桁数不正";
            }
        }
        // メールアドレス
        if (!empty($registData[7])) {
            if (!$this->checkDiditLessThan($registData[7], 80)) {
                $error[] = "「メールアドレス」桁数不正";
            } else {
                $apiManager = new ApiManager;
                $checkDomain = $apiManager->isDnsByMail($registData[7]);
                if (!$checkDomain) {
                    $error[] = "「メールアドレス」不正";
                }
            }
        } else {
            $error[] = "「メールアドレス」未入力";
        }

        if (!empty($error)) {
            $result = implode(', ', $error);
        }

        return $result;
    }

    private function checkDiditLessThan($value, int $didit): bool
    {
        if (strlen($value) > $didit) {
            return false;
        }

        return true;
    }

    private function checkDiditEqual($value, int $didit): bool
    {
        if (strlen($value) != $didit) {
            return false;
        }

        return true;
    }

    private function validateUploadFile(Request $request)
    {
        return \Validator::make($request->all(), [
            'csv_file' => 'required|file|mimetypes:text/plain,text/csv,application/vnd.ms-excel|mimes:csv,txt',
        ], [
            'csv_file.required'  => 'ファイルを選択してください。',
            'csv_file.file'      => 'ファイルアップロードに失敗しました。',
            'csv_file.mimetypes' => 'ファイル形式が不正です。',
            'csv_file.mimes'     => 'ファイル拡張子が異なります。',
        ]);
    }

    /**
     * CSVデータを基本型に基づいたJSONデータに変換(回答番号のみ)
     *
     * @param array $csvData
     * @return string
     */
    private function answerNoConvertToFormattedJson(array $csvData): string
    {
        $data = [];
        foreach ($csvData as $row) {
            $data[] = [
                "title"    => "",
                "category" => "",
                "question" => "",
                "answer1"  => "",
                "answer2"  => "",
                "answer3"  => "",
                "answer4"  => "",
                "answer"   => [
                    (int) ($row[8] ?? 0),
                ],
                "reason"   => "",
                "image"    => "",
                "multi"    => "",
            ];
        }

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * CSVデータを基本型に基づいたJSONデータに変換(回答)
     *
     * @param array $csvData
     * @return string
     */
    private function answerConvertToFormattedJson(array $csvData): string
    {
        $data = [];
        foreach ($csvData as $row) {
            $question = preg_replace('/●(.*?)●/', '<span>$1</span>', $row[3]);
            $reason = $row[10] ?? "";
            if (!empty($reason)) {
                $reason .= "【教科書該当ページ：" . $row[11] ?? null . $row[12] ?? null . "】";
            }

            $data[] = [
                "title"    => $row[1] ?? null,
                "category" => $row[2] ?? null,
                "question" => $question,
                "answer1"  => $row[4] ?? null,
                "answer2"  => $row[5] ?? null,
                "answer3"  => $row[6] ?? null,
                "answer4"  => $row[7] ?? null,
                "answer"   => [
                    (int) ($row[8] ?? 0),
                ],
                "reason"   => $reason,
                "image"    => $row[13] ?? "",
                "multi"    => $row[14] ?? "",
            ];
        }

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * 共通処理: CSVをJSONに変換し、ダウンロード
     *
     * @param Request $request
     * @param string $fileName
     * @param string $convertMethod
     * @return Response|view
     */
    private function processCsvFile(Request $request, string $fileName, string $convertMethod)
    {
        // ログインチェック
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['message'] = "";
        // validate処理
        $validator = $this->validateUploadFile($request);
        if ($validator->fails()) {
            $data['message'] = $validator->errors()->first('csv_file');

            return view('admin.answer', $data);
        }

        // CSVファイルを一時保存
        $path = $request->file('csv_file')->store('temp');
        $filePath = storage_path('app/' . $path);

        // CSVファイルを処理
        try {
            $file = fopen($filePath, 'r');
            $csvData = $this->detectAndConvertCsvEncoding($file);
            fclose($file);
        } catch (\Exception $e) {
            $data['message'] = "ファイル処理中にエラーが発生しました: " . $e->getMessage();

            return view('admin.answer', $data);
        }

        // ヘッダーを削除
        array_shift($csvData);

        // 空白行を除外
        $csvData = array_filter($csvData, function ($row) {
            return trim(implode('', $row)) !== '';
        });

        // JSONデータを生成
        $json = $this->$convertMethod($csvData);
        if ($json === false) {
            $data['message'] = "JSON生成中にエラーが発生しました。";

            return view('admin.answer', $data);
        }

        // ダウンロード用のヘッダーを設定
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ];

        return response($json, 200, $headers);
    }

    /**
     * CSVファイルの文字コードを検出し、UTF-8に変換してデータを取得
     *
     * @param resource $file
     * @return array
     * @throws Exception 文字コード検出エラーの場合
     */
    private function detectAndConvertCsvEncoding($file): array
    {
        $csvData = [];
        $firstLine = fgets($file);
        rewind($file);

        // 文字コードを検出
        $encoding = mb_detect_encoding($firstLine, ['SJIS', 'SJIS-win', 'UTF-8', 'EUC-JP', 'ISO-8859-1'], true);
        if (!$encoding) {
            throw new \Exception("文字コードの検出に失敗しました。CSVファイルの形式を確認してください。");
        }

        // 文字コードに応じて変換
        while (($line = fgetcsv($file)) !== false) {
            $csvData[] = array_map(fn($value) => mb_convert_encoding($value, 'UTF-8', $encoding), $line);
        }

        return $csvData;
    }
}
