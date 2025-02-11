<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\S3Manager;
use App\Managers\SfManager;
use App\Model\WoaCustomerTrackingMapping;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * woa_customer_tracking_mappingsのデータをSalesforceと突き合わせ、CSVファイルとしてS3にアップロードするバッチ処理コマンド。
 */
class UploadWoaCustomerTrackingMapping extends Command
{
    private const SF_TABLE = 'kjb_RegistrationHistory__c';
    // 返却用
    public const SUCCESS = 0;
    public const ERROR = -1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UploadWoaCustomerTrackingMapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'woa_customer_tracking_mappingsレコードをS3にアップロードする処理（アップロードされたデータはBigQueryに連携される）';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var SfManager
     */
    private $SfManager;

    /**
     * @var WoaCustomerTrackingMapping
     */
    private $woaCustomerTrackingMapping;

    /**
     * コンストラクタ
     *
     * @param SfManager
     * @param WoaCustomerTrackingMapping
     */
    public function __construct(
        SfManager $SfManager,
        WoaCustomerTrackingMapping $woaCustomerTrackingMapping
    ) {
        // ログ設定
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        $this->SfManager = $SfManager;
        $this->woaCustomerTrackingMapping = $woaCustomerTrackingMapping;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        ini_set('memory_limit', '1G');
        // ロガーの初期化・設定
        $this->logger->info('woa_customer_tracking_mappingsレコード同期バッチを開始します。');
        $startTime = microtime(true);

        try {
            $records = $this->getRecords();
            $csvRecords = $records['csvRecords'];
            $failedRecords = $records['failedRecords'];
            $file = $this->createCsvFile($csvRecords);
            $this->uploadToS3($file);
            $this->updateStatus($csvRecords, $failedRecords);
        } catch (\Exception $e) {
            $this->logger->error('バッチ処理が失敗しました: ' . $e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }

        // エラーがある場合、Sentryエラー通知
        if ($this->logger->countError() > 0) {
            $this->logger->notifyErrorToSentry();

            return self::ERROR;
        }

        $durationTime = microtime(true) - $startTime;
        $this->logger->info('バッチ実行時間 : ' . $durationTime . '秒');
        $this->logger->info('woa_customer_tracking_mappingsレコード同期バッチを終了します。');

        return self::SUCCESS;
    }

    /**
     * Salesforce データと突き合わせるためのレコードを取得
     *
     * @return array {Collection, Collection} CSV 用のデータと失敗データ
     */
    private function getRecords(): array
    {
        $csvRecords = [];
        $failedRecords = [];

        $this->woaCustomerTrackingMapping
            ->where('upload_status', config('ini.UPLOAD_STATUS')['pending'])
            ->chunkById(500, function (Collection $records) use (&$csvRecords, &$failedRecords) {
                $customerIds = $this->SfManager->addQuotesValues(
                    $records->pluck('customer_id')->toArray()
                );

                $sfCustomerIds = $this->getSfCustomerIds($customerIds);

                foreach ($records as $record) {
                    if (!isset($sfCustomerIds[$record->customer_id])) {
                        $this->logger->error("Salesforceデータなし: customer_id={$record->customer_id}");
                        if ($record->created_at->diffInDays(now()) > 0) {
                            $failedRecords[] = $record->id;
                        }
                        continue;
                    }

                    $csvRecords[] = [
                        'id'               => $record->id,
                        'customer_id'      => $record->customer_id,
                        'customer_id_hash' => $record->customer_id_hash,
                        'salesforce_id'    => $sfCustomerIds[$record->customer_id],
                    ];
                }
            });

        // コレクションを返す

        return [
            'csvRecords'    => collect($csvRecords),
            'failedRecords' => collect($failedRecords),
        ];
    }

    /**
     * 作成するCSVデータのSFレコードを取得
     *
     * @param array $woaCustomerIds
     * @return array
     */
    private function getSfCustomerIds(array $woaCustomerIds): array
    {
        if (empty($woaCustomerIds)) {
            return [];
        }

        $builder = DB::table(self::SF_TABLE)
            ->select('KR_kjb_customer__c', 'KR_web_customer_id__c')
            ->whereIn('KR_web_customer_id__c', $woaCustomerIds);

        // SQL形式に変換
        $query = $this->SfManager->convertToSql($builder);

        // SFからコメディカル登録履歴を取得
        $response = $this->SfManager->select($query);

        $sfCustomerIds = [];
        foreach ($response['records'] as $data) {
            $sfCustomerIds[$data['KR_web_customer_id__c']] = $data['KR_kjb_customer__c'];
        }

        return $sfCustomerIds;
    }

    /**
     * CSVファイルを作成
     *
     * @param Collection $csvRecords
     * @return false|resource
     */
    private function createCsvFile(Collection $csvRecords)
    {
        $file = tmpfile();
        fputcsv($file, ['customer_id', 'customer_id_hash', 'salesforce_id']);

        $csvRecords->each(function ($record) use ($file) {
            // 必要なフィールドのみを書き込み
            if (fputcsv($file, [
                $record['customer_id'],
                $record['customer_id_hash'],
                $record['salesforce_id']
            ]) === false) {
                $this->logger->error('CSV 書き込み失敗: ' . json_encode($record));
            }
        });

        return $file;
    }

    /**
     * CSVファイルをS3にアップロード
     *
     * @param resource $file
     * @return void
     *
     */
    private function uploadToS3($file): void
    {
        $dateString = now()->format('YmdH');
        $filename = "/to_gcp/woa/customer_id_mappings/customer_id_mappings_{$dateString}.csv";

        (new S3Manager('s3_gcp_data_share'))->uploadResource($filename, $file);
    }

    /**
     * ステータスを更新
     *
     * @param Collection
     * @param Collection
     *
     * @return void
     */
    private function updateStatus(Collection $csvRecords, Collection $failedRecords): void
    {
        $this->woaCustomerTrackingMapping->whereIn('id', $csvRecords->pluck('id'))->update([
            'upload_status' => config('ini.UPLOAD_STATUS')['success'],
            'updated_at'    => now(),
        ]);
        $this->woaCustomerTrackingMapping->whereIn('id', $failedRecords)->update([
            'upload_status' => config('ini.UPLOAD_STATUS')['failed'],
            'updated_at'    => now(),
        ]);
    }
}
