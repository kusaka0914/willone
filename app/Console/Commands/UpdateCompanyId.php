<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\SfOrderManager;
use App\Managers\UpdateCompanyIdManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateCompanyId extends Command
{
    private const ERROR = -1;

    // データの整理のみを実行（ワンタイム用）
    private const ARGUMENT_CLEAN = 'clean';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateCompanyId {arg?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'woa_opportunityにcompanyを紐づける処理';

    /**
     * @var ImportOpportunityManager
     */
    private $updateCompanyIdManager;

    /**
     * @var SfOrderManager
     */
    private $sfOrderManager;

    /**
     * @var BatchLogger
     */
    private $logger;

    /**
     * Create a new command instance.
     * @access public
     * @return void
     */
    public function __construct()
    {
        // ログ設定
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        $this->sfOrderManager = new SfOrderManager();
        $this->updateCompanyIdManager = new UpdateCompanyIdManager($this->logger);

        parent::__construct();
    }

    /**
     * Execute the console command.
     * @access public
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->logger->info('処理開始');

            // 引数取得
            $arg = $this->argument('arg');
            if ($arg == self::ARGUMENT_CLEAN) {
                // 整理したい場合はSFのデータの有無にかかわらず先に重複データを削除しておきたい
                $this->deleteDuplicateCompany();
            }

            // SFのコメディカルオーダー（kjb_opportunity__c）を起点として「★法人名」を含む求人一覧を取得する。
            $infoTitle = 'SFのコメディカルオーダー一覧取得処理';
            $this->logger->info($infoTitle . '開始');
            $orderList = $this->sfOrderManager->getAccountList();
            $count = count($orderList);
            $this->logger->info("{$count}件を取得しました");
            $this->logger->info($infoTitle . '終了');
            if (empty($count)) {
                $this->logger->info('対象データが0件のため処理を終了します');
                $this->logger->info('処理正常終了');

                return Command::SUCCESS;
            }

            // 突合～更新処理
            if ($arg != self::ARGUMENT_CLEAN) {
                $result = $this->updateCompanyIdManager->updateCompanyId($orderList);
                // SF側のデータの問題＋突合順によりcompany_nameが重複するケースもあるため、重複データの削除
                $this->deleteDuplicateCompany();
            }
            // 紐づくwoa_opportunity等をきれいにする
            $result = $this->updateCompanyIdManager->cleanData($orderList);
        } catch (\Throwable $e) {
            $this->logger->error(
                'woa_opportunityにcompanyを紐づける処理が失敗しました: '
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

    /**
     * company: 重複データの削除
     * @access private
     */
    private function deleteDuplicateCompany()
    {
        $this->logger->info('会社名の重複データの論理削除 開始');
        $this->updateCompanyIdManager->deleteDuplicateCompany();
        $this->logger->info('会社名の重複データの論理削除 終了');
    }
}
