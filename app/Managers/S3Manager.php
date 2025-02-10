<?php

namespace App\Managers;

use App\Services\NotificationService;
use Aws\S3\S3Client;
use Storage;

/**
 * S3関連Manager
 */
class S3Manager
{
    protected $s3 = null;
    /**
     * コンストラクタ
     *
     * @param String $diskName
     */
    public function __construct($diskName = 's3')
    {
        $this->s3 = Storage::disk($diskName);
    }

    /**
     * 最新のフォルダを取得する
     * @param  string $directory
     * @return string
     */
    public function pickUpTargetFolder($directory)
    {
        // フォルダ取得
        $folderLists = $this->s3->directories($directory);

        $timeLists = [];
        foreach ($folderLists as $row) {
            // example. "customer_data/kj_kja/20180309/14/"
            // 3番目が時間フォルダ
            $tempList = explode('/', $row);
            if (!empty($tempList[3])) {
                $timeLists[] = $tempList[3];
            }
        }
        if (count($timeLists) == 0) {
            return;
        }
        // ソート
        $targetFolder = array_unique($timeLists);
        rsort($targetFolder);

        return $targetFolder[0];
    }

    /**
     * S3にファイルが存在するかチェクする
     * @param  string $inputCsvPath
     * @return boolean
     */
    public function checkFileExists($inputCsvPath)
    {
        // S3ファイルの存在確認
        if (!$this->s3->exists($inputCsvPath)) {
            return false;
        }

        return true;
    }

    /**
     * S3からファイルをダウンロードする
     * @param  string $inputS3Path
     * @return file
     */
    public function downloadFile($inputS3Path)
    {
        // ファイルダウンロード
        try {
            $fileData = $this->s3->get($inputS3Path);

            return $fileData;
        } catch (\Exception $e) {
            throw new \Exception("CSVファイルをダウンロードできませんでした。" . $e->getMessage());
        }
    }

    /**
     * ファイルをフォルダへコピーする
     * @param  string $basePath
     * @param  string $copyPath
     * @return void
     */
    public function copyFile($basePath, $copyPath)
    {
        try {
            // ファイルコピーする
            $this->s3->copy($basePath, $copyPath);
        } catch (\Exception $e) {
            throw new \Exception("CSVファイルのコピーができませんでした。" . $e->getMessage());
        }
    }

    /**
     * S3へCSVアップロード
     * @param string $path
     * @param array $header
     * @param array $job_list
     * @param string $fromEncoding エンコーディング前文字コード
     * @param string $toEncoding エンコーディング後文字コード
     * @return
     */
    public function uploadData($path, $header, $lines, $fromEncoding = null, $toEncoding = null)
    {
        try {
            // 0件の為、CSVファイルを作成せずに終了
            if (empty($lines)) {
                return false;
            }
            // 一時ファイル生成
            $temp = tmpfile();

            // ヘッダ書込み
            fputcsv($temp, $header);

            // 項目書込み
            foreach ($lines as $csvData) {
                $line = [];

                foreach ($header as $key) {
                    if (!is_null($fromEncoding) && !is_null($toEncoding)) {
                        mb_convert_variables($toEncoding, $fromEncoding, $csvData[$key]);
                    }
                    $line[$key] = $csvData[$key];
                }
                fputcsv($temp, $line);
            }

            // S3へアップロード
            $s3put = $this->s3->put($path, $temp);
            if (!$s3put) {
                throw new \Exception("CSVファイルをS3へ保存できませんでした。保存先:" . $path);
            }
            // 一時ファイルクローズ
            fclose($temp);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("CSVファイルをS3へ保存できませんでした。" . $e->getMessage());
        }
    }

    /**
     * ファイルアップロード
     * @param string $fileName
     * @param string $filePath
     *
     */
    public function uploadFile(string $fileName, string $filePath)
    {
        try {
            $this->s3->put($fileName, file_get_contents($filePath));
        } catch (\Exception $e) {
            throw new \Exception("ファイルをS3へ保存できませんでした。" . $e->getMessage());
        }
    }

    /**
     * S3へファイルをアップロードする
     *
     * @param string $outputS3Path アップロード先のS3のパス
     * @param resource $resource アップロードするファイル等のリソース
     * @return void
     * @throws Exception
     */
    public function uploadResource(string $outputS3Path, $resource): void
    {
        // リソースが有効かどうかをチェック
        if (!is_resource($resource)) {
            throw new \Exception('無効なリソースが渡されました。アップロードを中止します。');
        }

        $filePath = '';
        try {
            // S3にファイルをアップロード
            $this->s3->put($outputS3Path, $resource);
        } catch (\Exception $e) {
            throw new \Exception('ファイルをアップロードできませんでした。' . $e->getMessage());
        } finally {
            if ($filePath && is_resource($filePath)) {
                fclose($filePath);
            }
        }
    }

    /**
     * ファイル存在チェック・ダウンロード
     *
     * @param string $inputS3Path
     * @return file
     */
    public function checkExistsAndDownload($inputS3Path)
    {
        // 存在チェック
        if ($this->checkFileExists($inputS3Path) === false) {
            throw new \Exception("S3にCSVファイルがありませんでした。" . $inputS3Path);
        }

        return $this->downloadFile($inputS3Path);
    }

    /**
     * ファイルバックアップ
     * 指定したサフィックスを付与してバックアップする（デフォルト".old"）
     *
     * @param string $filePath
     * @param string $suffix
     * @return void
     */
    public function backupFile($filePath, $suffix = '.old')
    {
        // バックアップファイル名
        $backFileName = $filePath . $suffix;
        $ret = true;
        try {
            if ($this->checkFileExists($backFileName) === true) {
                // リネーム後のファイルが存在する場合は削除
                $ret = $this->s3->delete($backFileName);
            }
            // リネーム後のファイルが存在しない または 削除が成功したらリネーム実行
            if ($ret) {
                $this->s3->move($filePath, $backFileName);
            }
        } catch (\Exception $e) {
            throw new \Exception("ファイルバックアップでエラーが発生しました。" . $e->getMessage());
        }
    }

    /**
     * 画像アップロード
     * @param string $dirPath
     * @param object $reqFile
     * @param string $fileName
     *
     */
    public function uploadImg(string $dirPath, object $reqFile, string $fileName)
    {
        $acl = $this->getS3ACL();
        try {
            $this->s3->putFileAs($dirPath, $reqFile, $fileName, $acl);
        } catch (\Exception $e) {
            throw new \Exception("画像の保存に失敗しました：{$dirPath}{$fileName}. エラー詳細: " . $e->getMessage());
        }
    }

    /**
     * ファイル削除
     * @param string $filePath
     * @return bool
     * @throws \Exception
     */
    public function deleteFile(string $filePath)
    {
        try {
            // ファイル削除にACLは不要
            return $this->s3->delete($filePath);
        } catch (\Exception $e) {
            throw new \Exception("ファイル削除でエラーが発生しました。：{$filePath}. エラー詳細: " . $e->getMessage());
        }
    }

    /**
     * dev環境では、他AWSアカウントのS3に接続するため、ACL => 'bucket-owner-full-control' の権限をファイルに付与する
     * ほか環境ではデフォルト（private）
     *
     * @return array $acl
     */
    private function getS3ACL(): array
    {
        $acl = ['ACL' => 'private'];
        if (env('APP_ENV') === 'development') {
            $acl = ['ACL' => 'bucket-owner-full-control'];
        }

        return $acl;
    }

    /**
     * S3バケットからオブジェクトリストを取得
     * @param string $bucket
     * @param string $folderPath
     * @param string $delimiter = null
     * @param bool $isExceptionThrow = false
     * @return object|null $objects
     */
    public function listObjects(string $bucket, string $folderPath, string $delimiter = null, bool $isExceptionThrow = false): ?object
    {
        try {
            $s3 = new S3Client([
                'version' => 'latest',
                'region'  => env('S3_CO_IMAGE_REGION'),
            ]);

            $option = [
                'Bucket' => $bucket,
                'Prefix' => $folderPath,
            ];
            if (!is_null($delimiter)) {
                $option['Delimiter'] = $delimiter;
            }
            $objects = $s3->listObjects($option);

            return $objects;
        } catch (\Exception $e) {
            if ($isExceptionThrow) {
                throw $e;
            } else {
                (new NotificationService)->sendSlack('s3_error', "S3のファイル一覧取得に失敗しました。\n{[$folderPath]}");
                \Log::error("S3のファイル一覧取得でエラーが発生しました。：{$folderPath}");
                \Log::error($e);

                return null;
            }
        }
    }
}
