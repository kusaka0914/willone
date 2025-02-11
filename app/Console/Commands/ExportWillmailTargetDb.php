<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\WillmailManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * WillmailターゲットDBエクスポートバッチ
 */
class ExportWillmailTargetDb extends Command
{
    // 返却用
    const ERROR = -1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportWillmailTargetDb {iniFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'willmailターゲットDBエクスポート処理';

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

    // ターゲットID
    private $targetDbId;

    // エクスポートパス
    private $exportPath;

    // willmailManager
    private $willMgr;

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
        if (empty($targetDbId) || empty($exportFilePath)) {
            throw new \Exception("{$className}: iniファイル設定値読込み失敗: {$iniFilePath}");
        }

        // エクスポート用設定
        $this->exportPath = $exportFilePath;
        $this->targetDbId = $targetDbId;

        // willmail エクスポート処理
        $this->willMgr = new WillmailManager($this->logger);
    }

    /**
     * API経由でWillmailターゲットDBエクスポート
     * @return boolean
     */
    public function handle()
    {
        // 事前処理
        $this->init();

        $this->logger->info('処理開始');

        // 対象ターゲットDBIDチェック
        if (is_null($this->targetDbId)) {
            $this->logger->error('対象ターゲットDBのIDが指定されていません。');
            $this->logger->info('処理終了');

            return self::ERROR;
        }

        $this->logger->info("ターゲットDB ID : " . $this->targetDbId);

        // 対象のWillmailターゲットDBをエクスポートする
        try {
            // willmail エクスポート処理
            $this->willMgr->exportWillmailDatabase($this->targetDbId, $this->exportPath);
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
