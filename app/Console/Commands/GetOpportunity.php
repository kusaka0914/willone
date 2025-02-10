<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\S3Manager;
use App\Managers\SfOrderManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GetOpportunity extends Command
{
    private const ERROR = -1;

    private const S3_UPLOAD_PATH = 'import/woa/';

    private const TEMP_CSV_FILE_NAME = 'kjb_opportunity__c.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetOpportunity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SFのコメディカルオーダーのデータを取得する処理';

    /**
     * @var SfOrderManager
     */
    private $sfOrderManager;

    /**
     * @var BatchLogger
     */
    private $logger;

    /**
     * @var mixed|\Illuminate\Config\Repository
     */
    private $config;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        SfOrderManager $sfOrderManager
    ) {
        // ログ設定
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        $this->config = config("batch.get_opportunity");

        $this->sfOrderManager = $sfOrderManager;

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

            $header = $this->config['csv']['header'];

            $orders = $this->sfOrderManager->getOrderList();

            $mappingHeaderBody = $this->config['csv']['mapping_header_to_db_item'];

            $lines = [];
            foreach ($orders as $order) {
                // 事業所項目の場合、項目名が $order['Account__r']['Name'] のように多次元配列になっているが、
                // 後続処理の便宜上 $order['Account__r.Name'] のように1次元配列にする
                foreach ($order['Account__r'] as $key => $itemName) {
                    $order["Account__r.$key"] = $itemName;
                    // SFの'AC_Advt_Stop_Flag__c'カラムがなくなるので疑似設定
                    // ad_attract_stop_flagをWOAから無くす場合はこれも消す
                    if ('GKeidoNumber__c' === $key) {
                        $order['Account__r.AC_Advt_Stop_Flag__c'] = false;
                    }
                }

                $line = [];
                foreach ($order as $key => $value) {
                    $headerName = array_search($key, $mappingHeaderBody);
                    if ($headerName !== false) {
                        // boolean型でfalseの場合、CSV出力時に値が空になってしまうのでint型を設定
                        if (gettype($value) === "boolean") {
                            $value = $value === true ? 1 : 0;
                        }
                        $line[$headerName] = $value;
                    }
                }

                $lines[] = $line;
            }

            // S3へのアップロード
            (new S3Manager('s3_batch'))->uploadData(
                self::S3_UPLOAD_PATH . self::TEMP_CSV_FILE_NAME,
                $header,
                $lines
            );

            $uploadCount = count($lines);
            $this->logger->info("{$uploadCount}件をアップロードしました");
        } catch (\Throwable $e) {
            $this->logger->error(
                'SFのコメディカルオーダーのデータについて、S3へのCSV保存処理が失敗しました: '
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
