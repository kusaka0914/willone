<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\S3Manager;
use App\Managers\WillmailManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * WillmailターゲットDB連携用バッチ
 */
class ImportWillmailTargetDb extends Command
{
    // 返却用
    const ERROR = -1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportWillmailTargetDb {iniFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'willmailターゲットDBインポート処理';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // ログ
    private $logger;

    // インポートファイルパス
    private $importFilePath;

    // s3manager
    private $s3Mng;

    /**
     * init処理
     *
     * @return void
     */
    private function init()
    {
        // ログ設定
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        // 引数取得とiniファイルパス生成
        $iniFilePath = app_path() . '/Console/ini/' . $this->argument('iniFile');

        // iniファイル読込み
        require_once $iniFilePath;

        // iniファイル設定値チェック
        if (empty($targetDbId) || empty($importFilePath)) {
            throw new \Exception("{$className}: iniファイル設定値読込み失敗: {$iniFilePath}");
        }

        // エクスポート用設定
        $this->importFilePath = $importFilePath;
        $this->targetDbId = $targetDbId;

        // ファイルの存在確認
        $this->s3Mng = new S3Manager();
    }

    /**
     * API経由でWillmailターゲットDBへ連携
     * @return boolean
     */
    public function handle()
    {
        // 事前処理
        $this->init();

        $this->logger->info('処理開始');
        $this->logger->info("ターゲットDB ID : " . $this->targetDbId);
        $this->logger->info("インポートCSVファイル : " . $this->importFilePath);

        // S3に格納されているCSVファイル読み込み、WillmailターゲットDBへ連携する
        try {
            // CSVファイルダウンロード
            $csvData = $this->s3Mng->checkExistsAndDownload($this->importFilePath);

            // willmail CSV一括更新処理
            $willmailMgr = new WillmailManager($this->logger);
            $willmailMgr->importWillmailDatabase($this->targetDbId, $csvData);

            // ファイルバックアップ
            $this->s3Mng->backupFile($this->importFilePath);
        } catch (\Exception $e) {
            $this->error($e->getMessage()); // コンソール出力
            $this->logger->error($e->getMessage());
            $this->logger->notifyErrorToSentry();
            $this->logger->info('処理終了');

            return self::ERROR;
        }

        $this->logger->info("処理終了");

        return Command::SUCCESS;
    }
}
