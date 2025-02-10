<?php

namespace App\UseCases\Commands;

use App\Google\Apis\Client as GoogleApiClient;
use App\Google\Apis\Drive as GoogleApiDrive;
use Exception;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Sheets;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_Request;
use Google_Service_Sheets_ValueRange;

class UploadGoogleDriveUseCase
{
    /**
     * Google Sheetsへのアップロード
     *
     * @param string $folderId
     * @param string $fileName
     * @param array $data
     * @param object $logger
     * @return string|bool
     */
    final public function __invoke(string $folderId, string $fileName, array $data, object $logger)
    {
        try {
            // インスタンス生成
            $googleApi = new GoogleApiClient();
            $apiClient = $googleApi->getClient();

            $googleDrive = new GoogleApiDrive();
            $driveService = new Google_Service_Drive($apiClient);
            $sheetsService = new Google_Service_Sheets($apiClient);

            $files = $googleDrive->fetchFiles($apiClient, $fileName, $folderId);

            // ファイルIDの取得
            $fileId = '';
            if (count($files->getFiles())) {
                $list = $files->getFiles();
                foreach ($list as $file) {
                    if ($file->name === $fileName) {
                        $fileId = $file->id;
                        break;
                    }
                }
            }

            $spreadsheetId = null;
            $sheetName = null;
            $sheetId = null;

            if ($fileId) {
                // 既存のファイルが見つかった場合、spreadsheetIdを取得
                $spreadsheetId = $fileId;
                // スプレッドシートが作成されたことを確認し、シート名を取得する
                $spreadsheet = $sheetsService->spreadsheets->get($spreadsheetId);
                $sheetName = $spreadsheet->getSheets()[0]->getProperties()->getTitle();
                $sheetId = $spreadsheet->getSheets()[0]->getProperties()->getSheetId();

                $logger->info("既存のスプレッドシートを使用: $spreadsheetId");
            } else {
                // 新しいスプレッドシートを作成
                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name'     => $fileName,
                    'mimeType' => 'application/vnd.google-apps.spreadsheet',
                    'parents'  => [$folderId], // フォルダIDを指定
                ]);

                $file = $driveService->files->create($fileMetadata, [
                    'supportsTeamDrives' => true,
                    'fields'             => 'id',
                ]);
                $spreadsheetId = $file->id;

                // スプレッドシートが作成されたことを確認し、シート名を取得する
                $spreadsheet = $sheetsService->spreadsheets->get($spreadsheetId);
                $sheetName = $spreadsheet->getSheets()[0]->getProperties()->getTitle();
                $sheetId = $spreadsheet->getSheets()[0]->getProperties()->getSheetId();

                $logger->info("新しいスプレッドシートを作成: $spreadsheetId");
            }

            // シート全体をクリアするリクエスト
            $requests = [
                new Google_Service_Sheets_Request([
                    'updateCells' => [
                        'range'  => [
                            'sheetId' => $sheetId,
                        ],
                        'fields' => 'userEnteredValue',
                    ],
                ]),
            ];
            $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => $requests,
            ]);
            $sheetsService->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
            $logger->info("既存のデータを削除しました: $spreadsheetId");

            // 削除後範囲の指定
            $cellRange = 'A1';
            $range = "{$sheetName}!{$cellRange}";

            // 新しいデータを追加
            $values = $data;
            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values,
            ]);
            $params = [
                'valueInputOption' => 'RAW',
            ];
            $sheetsService->spreadsheets_values->update($spreadsheetId, $range, $body, $params);

            $logger->info("スプレッドシートにデータを追加しました: $spreadsheetId");

            return $spreadsheetId; // 成功時にはスプレッドシートIDを返す
        } catch (Exception $e) {
            // エラーメッセージをログに記録
            $logger->error("Google Sheetsアップロードエラー: " . $e->getMessage());

            return false; // エラー時にはfalseを返す
        }
    }
}
