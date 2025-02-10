<?php

namespace App\Google\Apis;

use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class Drive
{
    /**
     * Gドライブへファイルの作成
     *
     * @param object $apiClient
     * @param string $fileName
     * @param array $fileInfo
     * @param string $folderId
     * @return void
     */
    public function createFile(object $apiClient, string $fileName, array $fileInfo, string $folderId)
    {
        $service = new Google_Service_Drive($apiClient);
        $metaData = $this->makeMetaData($fileName, $folderId);
        $service->files->create($metaData, $fileInfo);
    }

    /**
     * ファイル一覧の取得
     *
     * @param object $apiClient
     * @param string $fileName
     * @param string $folderId
     * @return object
     */
    public function fetchFiles(object $apiClient, string $fileName, string $folderId): object
    {
        $service = new Google_Service_Drive($apiClient);
        $query = "name='{$fileName}' and '{$folderId}' in parents and trashed = false";

        $files = $service->files->listFiles([
            'corpora'                   => 'user',
            'includeItemsFromAllDrives' => true,
            'supportsAllDrives'         => true,
            'fields'                    => 'files(id, name)',
            'q'                         => $query,
        ]);

        return $files;
    }

    /**
     * ファイルの更新
     *
     * @param object $apiClient
     * @param string $fileId
     * @param array $fileInfo
     * @return void
     */
    public function updateFile(object $apiClient, string $fileId, array $fileInfo)
    {
        $service = new Google_Service_Drive($apiClient);
        $empty = new Google_Service_Drive_DriveFile();
        $service->files->update($fileId, $empty, $fileInfo);
    }

    /**
     * metaデータの作成
     *
     * @param string $fileName
     * @param string $folderId
     * @return object
     */
    public function makeMetaData(string $fileName, string $folderId): object
    {
        $metaData = new Google_Service_Drive_DriveFile([
            'name'    => $fileName,
            'parents' => [$folderId],
        ]);

        return $metaData;
    }

    /**
     * ファイルのメタデータを作成
     *
     * @param string $content
     * @param string $mimeType
     * @param string $uploadType
     * @return array $fileInfo
     */
    public function makeFileInfo(string $content, string $mimeType, string $uploadType): array
    {
        $fileInfo = [
            'data'               => $content,
            'mimeType'           => $mimeType, // mime_content_type()では.csvをtext/csvで判定できない
            'uploadType'         => $uploadType,
            'supportsTeamDrives' => true,
            'fields'             => 'id',
        ];

        return $fileInfo;
    }
}
