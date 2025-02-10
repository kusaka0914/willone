<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\WoaOpportunity;
use Illuminate\Contracts\Validation\Validator as ValidatorResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as Validator;

/**
 * 注目枠求人を管理するコントローラ
 */
class PrOpportunityController extends Controller
{
    /**
     * @var int
     */
    private const ON_FLAG = 1;
    private const OFF_FLAG = 0;

    /**
     * 注目枠求人
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $woaOpportunity = new WoaOpportunity;

        $data = [
            'pr_opportunity' => $woaOpportunity->findPrOpportunity(),
        ];

        return view('admin.prOpportunity', $data);
    }

    /**
     * 注目枠求人詳細
     *
     * @param Request $request
     */
    public function detail(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data = [
            'pr_opportunity' => $this->findPrOpportunityById($request->id),
        ];

        return view('admin.prOpportunityDetail', $data);
    }

    /**
     * 注目枠求人更新
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $comment = "更新";

        if (!empty($request->add_flag)) {
            if (empty($request->sf_order_id)) {
                $data = [
                    'pr_opportunity' => $this->findAllPrOpportunity($request->id),
                    'message' => '登録に失敗しました。求人IDを入力してください。',
                    'class' => 'danger'
                ];
                return view('admin.prOpportunity', $data);
            }
            $targetOpportunity = $this->findOpportunityBySfOrderId($request->sf_order_id);
            $comment = "登録";
        } else {
            $targetOpportunity = $this->findPrOpportunityById($request->id);
        }

        if (empty($targetOpportunity)) {
            $data = [
                'pr_opportunity' => $this->findAllPrOpportunity(),
                'message' => $comment.'に失敗しました。求人情報が存在しません。',
                'class' => 'danger'
            ];
            return view('admin.prOpportunity', $data);
        }

        $targetOpportunity->pr_flag = self::ON_FLAG;
        $targetOpportunity->pr_display_position = $request->pr_display_position;

        $updateResult = $this->updateOpportunity($targetOpportunity);

        if (!$updateResult) {
            $data = [
                'pr_opportunity' => $targetOpportunity,
                'message' => $comment.'に失敗しました。',
                'class' => 'danger'
            ];
            return view('admin.prOpportunityDetail', $data);
        }

        $data = [
            'pr_opportunity' => $targetOpportunity,
            'message' => $comment.'に成功しました。',
            'class' => 'success'
        ];

        return view('admin.prOpportunityDetail', $data);
    }

    /**
     * 注目枠求人削除
     *
     * @param Request $request
     */
    public function delete(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $targetOpportunity = $this->findPrOpportunityById($request->id);

        $targetOpportunity->pr_flag = self::OFF_FLAG;

        $deleteResult = $this->updateOpportunity($targetOpportunity);

        if (!$deleteResult) {
            $data = [
                'pr_opportunity' => $this->findAllPrOpportunity(),
                'message' => '削除に失敗しました。',
                'class' => 'danger'
            ];
            return view('admin.prOpportunity', $data);
        }

        $data = [
            'pr_opportunity' => $this->findAllPrOpportunity(),
            'message' => '削除しました。',
            'class' => 'success'
        ];

        return view('admin.prOpportunity', $data);
    }

    /**
     * 注目枠求人CSVアップロード
     *
     * @param Request $request
     */
    public function upload(Request $request)
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
            $data = [
                'message' => 'CSVの登録に失敗しました。CSVファイルのフォーマットが正しいことを確認してださい。',
                'class' => 'danger',
                'pr_opportunity' => $this->findAllPrOpportunity(),
            ];

            return view('admin.prOpportunity', $data);
        }

        // CSVファイルをサーバーに一時保存
        $temporary_csv_file = $request->file('csv_file')->store('csv');
        $fp = fopen(storage_path('app/') . $temporary_csv_file, 'r');

        // カラム数チェック
        $headers = fgetcsv($fp);
        if (count($headers) != 2) {
            fclose($fp);
            Storage::delete($temporary_csv_file);
            $data = [
                'message' => 'CSVの登録に失敗しました。CSVファイルのフォーマットが正しいことを確認してださい。',
                'class' => 'danger',
                'pr_opportunity' => $this->findAllPrOpportunity(),
            ];

            return view('admin.prOpportunity', $data);
        }

        // 登録処理
        $rowCount = 0;
        $registCount = 0;
        $errorCount = 0;
        $errorRow = [];
        while ($row = fgetcsv($fp)) {
            $targetOpportunity = null;
            $rowCount++;
            mb_convert_variables('UTF-8', 'SJIS', $row);
            if (empty($row[0]) || empty($row[1]) || !in_array($row[1], config('ini.PR_DISPLAY_POSITION'))) {
                $errorRow[] = "【登録エラー】{$rowCount}行目：データ不正";
                continue;
            }

            $targetOpportunity = $this->findOpportunityBySfOrderId($row[0]);
            if (empty($targetOpportunity)) {
                $errorRow[] = "【登録エラー】{$rowCount}行目：求人IDが存在しません。";
                continue;
            }

            $targetOpportunity->pr_flag = self::ON_FLAG;
            $targetOpportunity->pr_display_position = $row[1];

            $addResult = $this->updateOpportunity($targetOpportunity);

            if (!$addResult) {
                $errorRow[] = "【登録エラー】{$rowCount}行目：データ不正";
                continue;
            }

            $registCount++;
        }

        // 一時ファイルを削除
        fclose($fp);
        Storage::delete($temporary_csv_file);
        // 完了メッセージを表示
        $errorCount = count($errorRow);
        $data = [
            'message' => "CSVの登録が完了しました。<br>成功件数：$registCount<br>エラー件数：$errorCount<br>",
            'errors' => $errorRow,
            'class' => 'info',
            'pr_opportunity' => $this->findAllPrOpportunity(),
        ];

        return view('admin.prOpportunity', $data);
    }

    /**
     * 注目枠求人をidで取得
     *
     * @param int $id
     * @return WoaOpportunity
     */
    private function findPrOpportunityById(int $id): WoaOpportunity
    {
        $woaOpportunity = new WoaOpportunity;

        return $woaOpportunity->findPrOpportunityById($id);
    }

    /**
     * 注目枠求人を全件取得
     *
     * @return Collection
     */
    private function findAllPrOpportunity(): Collection
    {
        $woaOpportunity = new WoaOpportunity;

        return $woaOpportunity->findPrOpportunity();
    }

    /**
     * コメディカルオーダーIDから求人を取得
     * @param string $sfOrderId
     * @return ?WoaOpportunity
     */
    private function findOpportunityBySfOrderId(string $sfOrderId): ?WoaOpportunity
    {
        $woaOpportunity = new WoaOpportunity;

        return $woaOpportunity->findBySfOrderId($sfOrderId);
    }

    /**
     * レコードを更新する
     * @param WoaOpportunity $woaOpportunity
     * @return boolean
     */
    private function updateOpportunity(WoaOpportunity $woaOpportunity): bool
    {
        DB::beginTransaction();
        try {
            $woaOpportunity->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

        return true;
    }

    /**
     * CSVファイルバリデーション
     *
     * @param Request $request
     * @return ValidatorResult
     */
    private function validateUploadFile(Request $request): ValidatorResult
    {
        return Validator::make($request->all(), [
            'csv_file' => 'required|file|mimetypes:text/plain|mimes:csv,txt',
        ], [
            'csv_file.required'  => 'ファイルを選択してください。',
            'csv_file.file'      => 'ファイルアップロードに失敗しました。',
            'csv_file.mimetypes' => 'ファイル形式が不正です。',
            'csv_file.mimes'     => 'ファイル拡張子が異なります。',
        ]);
    }
}
