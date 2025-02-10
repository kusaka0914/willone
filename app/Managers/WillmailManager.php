<?php
/**
 *  WillmailManager
 */

namespace App\Managers;

use GuzzleHttp\Client;
use Storage;

class WillmailManager
{
    private $WILLMAIL_ACCOUNT_KEY;
    private $WILLMAIL_API_KEY;
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
        $this->WILLMAIL_ACCOUNT_KEY = config('ini.WILLMAIL_ACCOUNT_KEY');
        $this->WILLMAIL_API_KEY = config('ini.WILLMAIL_API_KEY');
    }

    /**
     * CSVファイル一括更新
     * @param  string $dbid
     * @param  file $csvdata
     * @return void
     */
    public function importWillmailDatabase($dbid, $csvData)
    {
        if (empty($dbid) || empty($csvData)) {
            return;
        }
        $httpRequestUrl = config('ini.WILLMAIL_URL') . $this->WILLMAIL_ACCOUNT_KEY . '/' . $dbid . '/import?charset=Shift_JIS&mode=upsert&emptyCol=set_null';
        $this->logger->info("Willmail インポートAPI実行");

        try {
            $client = new Client(); //GuzzleHttp\Client
            $response = $client->request('POST', $httpRequestUrl, [
                'headers' => ['Content-Type' => "application/octet-stream"],
                'auth'    => [$this->WILLMAIL_ACCOUNT_KEY, $this->WILLMAIL_API_KEY],
                'body'    => $csvData,
            ]);
            $status = $response->getStatusCode();
            if ($status != 200) {
                // エラーの場合
                throw new \Exception(
                    'Willmail APIレスポンスが正常でありませんでした。status=' . $status . ' body=' . $response->getBody()
                );
            }
        } catch (\Exception $e) {
            throw new \Exception("ターゲットDBインポートに失敗しました。" . $e->getMessage());
        }
    }

    /**
     * WillmailエクスポートAPIを実行し、
     * レスポンスのCSVファイルをS3へ保存する
     *
     * @param int $dbid
     * @param string $s3Path
     * @param string $exportName
     * @return void
     */
    public function exportWillmailDatabase($dbid, $s3Path, $exportName = null)
    {
        if (empty($dbid)) {
            return;
        }
        // export api url
        $exportUrl = config('ini.WILLMAIL_URL') . $this->WILLMAIL_ACCOUNT_KEY . '/' . $dbid . '/export';

        try {
            $client = new Client(); //GuzzleHttp\Client
            $response = $client->request('GET', $exportUrl, [
                'headers' => ['Content-Type' => "text/csv"],
                'auth'    => [$this->WILLMAIL_ACCOUNT_KEY, $this->WILLMAIL_API_KEY],
            ]);
            $status = $response->getStatusCode();
            if ($status != 200) {
                // エラーの場合
                throw new \Exception(
                    'レスポンスが正常でありませんでした。status=' . $status . ' body=' . $response->getBody()
                );
            }
            // 出力ファイル名
            $fileName = $exportName;
            if (empty($fileName)) {
                // Content-Dispositionからファイル名を抽出
                $disposition = $response->getHeader('Content-Disposition');
                $fileName = $this->pickupFileName($disposition[0]);
                if (empty($fileName)) {
                    throw new \Exception("APIレスポンスからファイル名が取得できませんでした。disposition=" . $disposition);
                }
            }
            $this->logger->info("エクスポートファイル： " . $fileName);
            $contens = $response->getBody()->getContents();

            $uploadPath = $s3Path . $fileName;
            $this->logger->info("エクスポートファイル保存先： " . $uploadPath);

            // out of memory回避のため、一時ファイルに出力する
            $tempFile = tmpfile();
            fwrite($tempFile, $contens);
            // 一時ファイル出力したので、レスポンスデータは解放
            unset($contens);

            // S3へアップロード
            $s3 = Storage::disk('s3_mail');
            $s3->put($uploadPath, $tempFile);

        } catch (\Exception $e) {
            throw new \Exception("ターゲットDBエクスポートに失敗しました。" . $e->getMessage());
        } finally {
            // 一時ファイル削除
            if (isset($tempFile) && is_resource($tempFile)) {
                fclose($tempFile);
            }
        }
    }

    /**
     * レスポンスからファイル名を抽出
     *
     * @param string $target
     * @return string
     */
    private function pickupFileName($target)
    {
        // ファイル名を抽出する
        preg_match('/filename[^;=\n]*=(([\'\"]).*?\2|[^;\n]*)/', $target, $matches);
        $fileName = preg_replace('/[\'\"]/', '', $matches[1]);
        $fileName = str_replace('UTF-8', '', $fileName);
        // URLデコードする
        $fileName = urldecode($fileName);

        return $fileName;
    }
}
