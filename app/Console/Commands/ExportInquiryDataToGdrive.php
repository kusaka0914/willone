<?php
namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\UseCases\Commands\GetInquiryDataUseCase;
use App\UseCases\Commands\UploadGoogleDriveUseCase;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ExportInquiryDataToGdrive extends Command
{
    // 返却用
    const SUCCESS = 0;
    const ERROR = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportInquiryDataToGdrive {iniFilePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '問い合わせのスプレッドシートをGドライブにアップロード';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // ログマネージャ
    private $logger;

    /**
     * Gドライブ出力先フォルダーID
     *
     * @var string
     */
    private $folderId;

    // スプレッドシートのテンプレートID、名前、ヘッダーのマッピング
    private $sheets;

    /**
     * init処理
     */
    private function init()
    {
        $className = class_basename(get_class());

        // 引数取得とiniファイルパス生成
        $iniFilePath = $this->argument('iniFilePath');

        // iniファイル読込み
        require_once $iniFilePath;

        // iniファイル設定値チェック
        if (empty($folderId) || empty($memoryLimit) || empty($sheets)) {
            throw new \Exception("{$className}: iniファイル設定値読込み失敗: {$iniFilePath}");
        }

        $this->folderId = $folderId;
        $this->sheets = $sheets;

        // ログ設定
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        // 設定
        mb_internal_encoding('UTF-8');
        mb_language('Japanese');
        $this->logger->info("現在のメモリサイズ：" . ini_get('memory_limit'));
        if (!empty($memoryLimit)) {
            // メモリサイズ変更
            ini_set('memory_limit', $memoryLimit);
            $this->logger->info($iniFilePath . "からメモリサイズ変更:{$memoryLimit}");
        }
        $this->logger->info("変更後のメモリサイズ：" . ini_get('memory_limit'));
    }

    /**
     * スプレッドシートエクスポート
     * @return int 結果
     */
    public function handle(): int
    {
        // 事前処理
        $this->init();
        $this->logger->info('処理開始');

        foreach ($this->sheets as $sheet) {
            $result = $this->main($sheet['template_id'], $sheet['sheet_name'], $sheet['headers']);
            // エラー確認（0件の場合は処理を続ける）
            if ($result == self::ERROR) {
                $this->logger->notifyErrorToSentry();

                return self::ERROR;
            }
        }

        $this->logger->info("処理終了");

        return self::SUCCESS;
    }

    /**
     * メイン処理（データの取得～Gドライブへのアップロード）
     *
     * @param string $templateId
     * @param string $sheetName
     * @param array $headers
     * @return int
     */
    private function main(string $templateId, string $sheetName, array $headers): int
    {
        // DBからレコードを取得(registration_inquiry_detail)
        $inquiryData = (new GetInquiryDataUseCase)($templateId);

        if ($inquiryData->isEmpty()) {
            $this->logger->info("テンプレートID: {$templateId} のデータが存在しませんでした。");

            return self::SUCCESS; // データが存在しない場合は成功とみなす
        }

        // 変換処理
        $inquiryValues = [$headers]; // ヘッダーを追加
        // コレクションの各アイテムをループし、値を格納
        foreach ($inquiryData as $item) {
            $row = [];
            foreach ($headers as $header) {
                $key = strtolower($header); // ヘッダーをキーに変換
                $row[] = $item->$key ?? ''; // キーが存在しない場合は空文字を追加
            }
            $inquiryValues[] = $row;
        }

        // GoogleDriveへのアップロード
        $fileId = (new UploadGoogleDriveUseCase)($this->folderId, $sheetName, $inquiryValues, $this->logger);

        if ($fileId) {
            $this->logger->info("スプレッドシートをGドライブにアップロードしました\nテンプレートID: {$templateId}\nファイルID: " . $fileId);
        } else {
            $this->logger->error("スプレッドシートのアップロードに失敗しました。テンプレートID: {$templateId}");

            return self::ERROR;
        }

        return self::SUCCESS;
    }
}
