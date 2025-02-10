<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\ImportOpportunityManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportOpportunity extends Command
{
    private const ERROR = -1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportOpportunity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SFのオーダーからwoa_opportunityへのインポート処理';

    /**
     * @var ImportOpportunityManager
     */
    private $importOpportunityManager;

    /**
     * @var BatchLogger
     */
    private $logger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ログ設定
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        $this->config = config("batch.import_opportunity");

        // メモリサイズ変更
        ini_set('memory_limit', $this->config["memory_limit"]);

        $this->importOpportunityManager = new ImportOpportunityManager($this->logger);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->logger->info('処理開始');

            // Web（Search）とFeed用に加工された求人一覧を取得
            $this->logger->info('Web、Feed用の求人一覧取得・編集・除外処理開始');
            // 登録、更新対象データ取得
            $orderForWebAndFeed = $this->importOpportunityManager->generateOpportunityForWebAndFeed();
            $orderForWebAndFeedCount = count($orderForWebAndFeed);
            $this->logger->info("{$orderForWebAndFeedCount}件を取得しました");
            $this->logger->info('Web、Feed用の求人一覧取得・編集・除外処理終了');

            if (empty($orderForWebAndFeed)) {
                $this->logger->info('対象データが0件のため処理を終了します');
                $this->logger->info('処理正常終了');

                return Command::SUCCESS;
            }

            // Feed用のCSVをS3に出力する
            $this->logger->info('Feed用のCSV出力処理開始');
            $this->importOpportunityManager->uploadFeedCsv($orderForWebAndFeed);
            $this->logger->info('Feed用のCSV出力処理終了');

            // Webのみのためのオーダー情報の編集
            $this->logger->info('Webのみ用の求人データ編集処理開始');
            $orderForWeb = $this->importOpportunityManager->editSfOrderForWeb($orderForWebAndFeed);
            $this->logger->info('Webのみ用の求人データ編集処理終了');

            // woa_opportunity テーブルのUpsert
            $this->logger->info('woa_opportunityテーブルへのUpsert処理開始');
            $upsertCount = $this->importOpportunityManager->upsertOrder($orderForWeb);
            $this->logger->info("{$upsertCount}件の差分があり、Upsertされました");
            $this->logger->info('woa_opportunityテーブルへのUpsert処理終了');

            // すでにwoa_opportunityに存在するデータの内、upsert対象にないレコードを論理削除する
            $this->logger->info('woa_opportunityテーブルの論理削除処理開始');
            $softDeleteCount = $this->importOpportunityManager->softDeleteOrder($orderForWeb);
            $this->logger->info("{$softDeleteCount}件を論理削除しました");
            $this->logger->info('woa_opportunityテーブルの論理削除処理終了');
        } catch (\Throwable $e) {
            $this->logger->error(
                'SFのオーダーからwoa_opportunityへのインポート処理が失敗しました: '
                . ' file=' . $e->getFile()
                . ' line=' . $e->getLine()
                . ' error_message=' . $e->getMessage()
            );
            // Sentryエラー通知
            $this->logger->notifyErrorToSentry();
            $this->logger->info('処理異常終了');

            return self::ERROR;
        }

        $this->logger->info('処理正常終了');

        return Command::SUCCESS;
    }
}
