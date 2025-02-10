<?php

namespace App\Services;

class GenerateFeedXmlService
{
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * 行数をカウントし、ファイルに書き込む
     *
     * @param string $content ファイルに書き込む内容
     * @param resource $file ファイルリソース
     * @param array $count 行数を格納する配列
     * @return array ファイルリソースを含む配列
     */
    public function countLine(string $content, $file, array $count): array
    {
        if (fwrite($file, $content)) {
            $count['normal']++;
        } else {
            $this->logger->error("\n" . 'Error: ファイルに ' . $content . ' を書き込めませんでした。');

            $count['abnormal']++;
        }

        return [$file, $count];
    }

    /**
     * 指定されたFTPタイプにファイルをアップロードする
     *
     * @param array $config アップロードする定義
     * @throws \Exception FTP接続やログイン、アップロードに失敗した場合にスローされる
     * @return void
     */
    public function uploadToFtp(array $config): void
    {
        $ftpConfig = config("ftp.{$config['ftp_type']}");
        $outputFilePath = base_path("public/{$config['file_path']}");

        // 環境に応じてルートディレクトリを設定
        $fileName = app()->environment('production') ? $ftpConfig['filename'] : 'DEV_' . $ftpConfig['filename'];
        $protocol = $ftpConfig['protocol'];
        try {
            $curl = curl_init();

            if (!$curl) {
                throw new \Exception('cURLの初期化に失敗しました');
            }
            // URL設定
            $url = "{$protocol}://{$ftpConfig['host']}:{$ftpConfig['port']}/{$fileName}.xml";
            // cURLオプション設定
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_USERPWD, "{$ftpConfig['username']}:{$ftpConfig['password']}");
            curl_setopt($curl, CURLOPT_UPLOAD, 1);
            // プロトコルに応じて設定を変更
            if ($protocol === 'sftp') {
                curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
            } else {
                curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_FTP);
            }
            curl_setopt($curl, CURLOPT_INFILESIZE, filesize($outputFilePath));
            // ファイルストリームを開く
            $fileStream = fopen($outputFilePath, 'r');
            if (!$fileStream) {
                throw new \Exception("ファイルのオープンに失敗しました: $outputFilePath");
            }
            curl_setopt($curl, CURLOPT_INFILE, $fileStream);
            // 実行
            $result = curl_exec($curl);
            if (!$result) {
                throw new \Exception("ファイルのアップロード中にエラーが発生しました: " . curl_error($curl));
            }
            // クローズ
            fclose($fileStream);
            curl_close($curl);

            // ログ
            $this->logger->info("$fileName のアップロードに成功しました（{$config['ftp_type']} FTP）。");
        } catch (\Exception $e) {
            $this->logger->error("FTPエラー: " . $e->getMessage());
        }
    }

    /**
     * フィードディレクトリの準備
     *
     * @return string|null 準備されたディレクトリのパス、失敗した場合はnull
     */
    public function prepareFeedDirectory(): ?string
    {
        $pathFeed = base_path('public/feed/');
        if (!is_dir($pathFeed) && !mkdir($pathFeed, 0755, true)) {
            $this->logger->error("Failed to create directory: {$pathFeed}");

            return null;
        }

        return $pathFeed;
    }

    /**
     * 既存のフィードファイルを削除
     *
     * @param string $filePath 削除するファイルのパス
     * @return void
     */
    public function deleteExistingFile(string $filePath): void
    {
        if (file_exists($filePath) && !unlink($filePath)) {
            $this->logger->error("Failed to delete file: {$filePath}");
        }
    }
}
