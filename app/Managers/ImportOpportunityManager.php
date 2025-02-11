<?php

namespace App\Managers;

use App\Logging\BatchLogger;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Services\BigQueryService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use SplFileObject;

class ImportOpportunityManager
{
    /**
     * SFオーダーCSV格納先のS3ディレクトリ
     */
    private const S3_PATH_FOR_SF_OPPORTUNITY = 'import/woa/';

    /**
     * SFオーダーCSVのファイル名
     */
    private const CSV_FILE_NAME_FOR_SF_OPPORTUNITY = 'kjb_opportunity__c.csv';

    /**
     * ターゲットDBのCSVのファイル名
     */
    private const CSV_FILE_NAME_FOR_TARGETDB = 'kyusyokusya_woa_mm.csv';

    /**
     * Feed用CSV出力先のS3ディレクトリ
     */
    private const S3_PATH_FOR_FEED = 'export/woa/';

    /**
     * Feed用CSVのファイル名
     */
    private const CSV_FOR_FEED_FILE_NAME = 'woa_opportunity_for_feed.csv';

    /**
     * CSVのヘッダーの位置（行）
     */
    private const CSV_HEADER_POSITION = 0;

    /**
     * フラグON
     */
    private const FLAG_ON = 1;

    /**
     * フラグOFF
     */
    private const FLAG_OFF = 0;

    /**
     * 事業所名公開フラグ名（ON）
     */
    private const PUBLICLY_FLAG_ON_NAME = '公開';

    /**
     * オーダーステータス（ON）
     */
    private const ORDER_STATUS_ON = '○';

    /**
     * 給与の共通文言
     */
    private const SALARY_COMMON_SENTENCE = 'お問合せください';

    /**
     * 施設形態ID（その他）
     */
    private const OTHER_BUSINESS_ID = '99';

    /**
     * @var WoaOpportunityManager
     */
    private $woaOpportunityManager;

    /**
     * @var BatchLogger
     */
    private $logger;

    /**
     * @var MasterAddr1Mst
     */
    private $masterAddr1Mst;

    /**
     * @var MasterAddr2Mst
     */
    private $masterAddr2Mst;

    /**
     * @var mixed|\Illuminate\Config\Repository
     */
    private $config;

    public function __construct(BatchLogger $logger)
    {
        $this->config = config("batch.import_opportunity");
        $this->logger = $logger;

        $this->woaOpportunityManager = new WoaOpportunityManager();
        $this->masterAddr1Mst = new MasterAddr1Mst();
        $this->masterAddr2Mst = new MasterAddr2Mst();
    }

    /**
     * Web用とFeed用の共通の形式で求人一覧を生成する
     *
     * @return array 求人一覧
     */
    public function generateOpportunityForWebAndFeed(): array
    {
        // 未加工のSFオーダーの取得
        $orders = $this->getSfOrder();

        if (empty($orders)) {
            return [];
        }

        // 不要データの除外
        $orders = $this->removeUnnecessaryData($orders);

        // FeedとWeb共通の編集処理
        $convertedOrders = [];
        foreach ($orders as $order) {
            $convertedOrders[] = $this->editSfOrderForWebAndFeed($order);
        }

        // 編集後、不要データ除外

        return $this->removeUnnecessaryDataForAfterEdit($convertedOrders);
    }

    /**
     * 加工されてないSFのオーダー一覧を取得
     *
     * @return array SFオーダー一覧
     */
    private function getSfOrder(): array
    {
        $orderStr = (new S3Manager('s3_batch'))->downloadFile(self::S3_PATH_FOR_SF_OPPORTUNITY . self::CSV_FILE_NAME_FOR_SF_OPPORTUNITY);

        $tempFile = tmpfile();
        $meta = stream_get_meta_data($tempFile);
        fwrite($tempFile, $orderStr);
        $fileObj = new SplFileObject($meta['uri']);
        $fileObj->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        // 一時ファイル出力したのでダウンロードデータはここで開放
        unset($orderStr);

        $header = "";
        $orders = [];
        foreach ($fileObj as $index => $data) {
            // CSVヘッダー
            if ($index == self::CSV_HEADER_POSITION) {
                $header = $data;
                // CSV本体
            } else {
                // ヘッダーの項目名を配列のキー、本体部分の値を配列の値として連想配列型式に変換
                $orders[] = array_combine($header, $data);
            }
        }

        return $orders;
    }

    /**
     * ターゲットDB用のCSVデータを取得
     *
     * @return array SFオーダー一覧
     */
    public function getTargetDbData(): array
    {
        $orderStr = (new S3Manager('s3'))->downloadFile(self::S3_PATH_FOR_SF_OPPORTUNITY . self::CSV_FILE_NAME_FOR_TARGETDB);

        $tempFile = tmpfile();
        $meta = stream_get_meta_data($tempFile);
        fwrite($tempFile, $orderStr);
        $fileObj = new SplFileObject($meta['uri']);
        $fileObj->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        // 一時ファイル出力したのでダウンロードデータはここで開放
        unset($orderStr);

        $header = "";
        $orders = [];
        foreach ($fileObj as $index => $data) {
            // CSVヘッダー
            if ($index == self::CSV_HEADER_POSITION) {
                $header = $data;
                // CSV本体
            } else {
                // ヘッダーの項目名を配列のキー、本体部分の値を配列の値として連想配列型式に変換
                $orders[] = array_combine($header, $data);
            }
        }

        return $orders;
    }

    /**
     * 不要データを除外する
     *
     * @param array $orders
     * @return array
     */
    private function removeUnnecessaryData(array $orders): array
    {
        foreach ($orders as $index => $order) {
            // オーダー名
            if ($this->isHaveInvalidName($order['order_name'])) {
                $this->logger->info("連携対象外のオーダー名(order_name)のため除外されました: job_id={$order['job_id']}, order_name={$order['order_name']}");
                unset($orders[$index]);
            } elseif ($this->isHaveInvalidOfficeName($order['office_name'])) {
                $this->logger->info("連携対象外の事業所名(office_name)のため除外されました: job_id={$order['job_id']}, office_name={$order['office_name']}");
                unset($orders[$index]);
            }
            // オーダー最終確認日
            if (empty($order['last_confirmed_datetime'])) {
                $this->logger->info("オーダー最終確認日(last_confirmed_datetime)が空のため除外されました: job_id={$order['job_id']}, office_name={$order['office_name']}");
                unset($orders[$index]);
            }
        }

        return $orders;
    }

    /**
     * 編集後データに対して不要データを除外する
     *
     * @param array $orders
     * @return array
     */
    private function removeUnnecessaryDataForAfterEdit(array $orders): array
    {
        foreach ($orders as $index => $order) {
            if ($this->isJobTypeEmpty($order['job_type'])) {
                $this->logger->info("募集職種(job_type)が設定されていないため除外されました: job_id={$order['job_id']}");
                unset($orders[$index]);
            } elseif ($this->isInvalidAddr1($order['addr1'])) {
                $this->logger->info("都道府県ID(addr1)が設定されていないため除外されました: job_id={$order['job_id']}");
                unset($orders[$index]);
            } elseif ($this->isAddr2Empty($order['addr2'])) {
                $this->logger->info("市区町村ID(addr2)が設定されていないため除外されました: job_id={$order['job_id']}");
                unset($orders[$index]);
            } elseif ($this->isEmploymentTypeEmpty($order['employment_type'])) {
                $this->logger->info("雇用形態(employment_type)が設定されていないため除外されました: job_id={$order['job_id']}");
                unset($orders[$index]);
            }
        }

        return $orders;
    }

    /**
     * 不正なオーダー名が含まれているかどうか
     *
     * @param string $orderName
     * @return bool
     */
    private function isHaveInvalidName(string $orderName): bool
    {
        foreach ($this->config['invalid_order_name_list'] as $value) {
            if (strpos($orderName, $value) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 不正な事業所名が含まれているかどうか
     *
     * @param string $officeName
     * @return bool
     */
    private function isHaveInvalidOfficeName(string $officeName): bool
    {
        foreach ($this->config['invalid_office_name_list'] as $value) {
            if (strpos($officeName, $value) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 募集職種が設定されているかどうか
     *
     * @param string $jobType 募集職種
     * @return bool
     */
    private function isJobTypeEmpty(string $jobType): bool
    {
        return empty($jobType);
    }

    /**
     * 不正な都道府県IDが含まれているかどうか
     *
     * @param int|null $addr1 都道府県ID
     * @return bool
     */
    private function isInvalidAddr1(?int $addr1): bool
    {
        if (empty($addr1)) {
            return true;
        }

        foreach ($this->config['invalid_addr1'] as $value) {
            if (strpos((string) $addr1, (string) $value) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 雇用形態が設定されているかどうか
     *
     * @param string $employmentType 雇用形態
     * @return bool
     */
    private function isEmploymentTypeEmpty(string $employmentType): bool
    {
        return empty($employmentType);
    }

    /**
     * 市区町村IDが設定されているかどうか
     *
     * @param int|null $addr2 市区町村ID
     * @return bool
     */
    private function isAddr2Empty(?int $addr2): bool
    {
        return empty($addr2);
    }

    /**
     * SF求人一覧をWeb、Feed用に編集する
     *
     * @param array $order
     * @return array
     */
    public function editSfOrderForWebAndFeed(array $order): array
    {
        // Web用の編集後のレコード
        $converted = [];

        // 重複の除去
        $order = $this->removeDuplicatedValues($order);

        // 雇用形態の編集
        $editedEmploymentType = $this->editEmploy($order['employment_type'], $order['order_name']);

        $converted['job_id'] = $order['job_id'];
        $converted['sf_order_id'] = $order['sf_order_id'];
        $converted['sf_account_id'] = $order['sf_account_id'];
        $converted['business_id'] = $this->convertBusiness($order['business']);
        $converted['inq_number'] = $order['inq_number'];
        $converted['site_id'] = $order['site_id'];
        $converted['title'] = $order['title'];
        $converted['employment_type'] = str_replace(';', '/', $editedEmploymentType);
        $converted['detail'] = $order['detail'];
        $converted['salary'] = $this->convertSalary($order['salary']);
        $converted['worktime'] = $order['worktime'];
        $converted['closest'] = $order['closest'];
        $converted['dayoff'] = $this->replaceDayOff($order['dayoff']);
        $converted['office_name'] = $this->editOfficeName($order['office_name']);
        $converted['office_name_kana'] = $order['office_name_kana'];
        $converted['company_name'] = $order['company_name'];
        $converted['company_name_kana'] = $order['company_name_kana'];
        $converted['medical_category'] = $order['medical_category'];
        $converted['facilities'] = $order['facilities'];
        $converted['hp_url'] = $order['hp_url'];
        $converted['job_type'] = $this->convertJobType($order['job_type']);
        $converted['postal_code'] = $order['postal_code'];
        $converted['addr1'] = $this->masterAddr1Mst->getPrefIds()->get($order['addr1_name']);
        $converted['addr1_name'] = $order['addr1_name'];
        $addr2 = $this->masterAddr2Mst->getAddr2List()
            ->where('addr1_id', $converted['addr1'])
            ->where('addr2', $order['addr2_name'])
            ->first();
        $converted['addr2'] = !empty($addr2['id']) ? $addr2['id'] : null;
        $converted['addr2_name'] = $order['addr2_name'];
        $converted['addr'] = $this->generateAddr($order['addr1_name'], $order['addr2_name'], $order['addr_street']);
        $converted['g_latitude'] = $order['g_latitude'];
        $converted['g_longitude'] = $order['g_longitude'];
        $converted['employ'] = $this->convertEmploy($editedEmploymentType);
        $converted['worklocation'] = $this->generateWorkLocation($order['addr1_name'], $order['addr2_name']);
        $converted['business'] = str_replace(';', '/', $order['business']);
        $converted['station1'] = $order['station1'];
        $converted['station2'] = $order['station2'];
        $converted['station3'] = $order['station3'];
        $converted['minutes_walk1'] = $order['minutes_walk1'];
        $converted['minutes_walk2'] = $order['minutes_walk2'];
        $converted['minutes_walk3'] = $order['minutes_walk3'];
        $converted['prev_year_bounus'] = $order['prev_year_bounus'];
        $converted['annual_income_min'] = $order['annual_income_min'];
        $converted['annual_income_max'] = $order['annual_income_max'];
        $converted['nenkankyuujitsu'] = $order['nenkankyuujitsu'];
        $converted['ad_attract_stop_flag'] = $order['ad_attract_stop_flag'];
        $converted['account_name_flag'] = $order['account_name_flag'];
        $converted['publicly_flag'] = $order['publicly_flag'];
        $converted['webpublicly_flag'] = self::FLAG_ON; // 移行前も固定「1」
        $converted['job_type_name_for_feed'] = str_replace(';', '/', $order['job_type']);
        $converted['order_pr_title'] = $order['order_pr_title'];
        $converted['exist_order_flag'] = $this->convertOrderStatusToExistOrderFlag($order['order_status']);
        $converted['human_resource'] = $order['human_resource'];
        $converted['last_confirmed_datetime'] = Carbon::parse($order['last_confirmed_datetime'])->timezone('Asia/Tokyo')->format('Y-m-d H:i:s');

        return $converted;
    }

    /**
     * Web用に求人一覧を編集する
     *
     * @param array $orders 求人一覧
     * @return array 編集後の求人一覧
     */
    public function editSfOrderForWeb(array $orders): array
    {
        $convertedOrders = [];
        $queryResults = app(BigQueryService::class)->runQuery("SELECT job_offer_number, sf_account_id, reception_date FROM `hellowork.hellowork_sf_match_all` WHERE DATE(_PARTITIONTIME) = CURRENT_DATE('Asia/Tokyo') AND REGEXP_CONTAINS(service, 'WOA')");
        if (!$queryResults->isComplete()) {
            throw new \Exception('BigQueryのデータ取得でfalseが返され失敗しました!');
        }
        $queryResults = array_column(iterator_to_array($queryResults), 'sf_account_id');
        foreach ($orders as $order) {
            $order['publicly_flag'] = $this->generatePubliclyFlag($order['account_name_flag']);
            $order['hw_flag'] = $this->generateHwFlag($order['sf_account_id'], $queryResults);

            $convertedOrders[] = $order;
        }

        return $convertedOrders;
    }

    /**
     * 雇用形態の編集
     *
     * @param string $employmentType 雇用形態
     * @param string $orderName オーダー名
     * @return string 編集後雇用形態
     */
    private function editEmploy(string $employmentType, string $orderName): string
    {
        // 最初から雇用形態が設定されている場合はそのままの値を使う
        if (!empty($employmentType)) {
            return $employmentType;
        }

        foreach ($this->config['order_name_for_part_time'] as $value) {
            if (strpos($orderName, $value) !== false) {
                $employmentType = '非常勤';
            }
        }

        return $employmentType;
    }

    /**
     * 雇用形態の変換
     *
     * @param string $employmentType
     * @return string
     */
    private function convertEmploy(string $employmentType): string
    {
        $employmentTypeList = explode(';', $employmentType);

        $mapping = $this->config['conversion_sf_to_web']['employment_type'];
        foreach ($employmentTypeList as $index => $employmentType) {
            $employmentTypeList[$index] = !empty($mapping[$employmentType]) ? $mapping[$employmentType] : "";
        }

        return implode(",", $employmentTypeList);
    }

    /**
     * オーダーステータスをフラグに変換する
     *
     * @param string $orderStatus オーダーステータス
     * @return int
     */
    private function convertOrderStatusToExistOrderFlag(string $orderStatus): int
    {
        return $orderStatus === self::ORDER_STATUS_ON ? self::FLAG_ON : self::FLAG_OFF;
    }

    /**
     * 休日の一部文字列置換
     *
     * @param string $dayOff 休日
     * @return string
     */
    private function replaceDayOff(string $dayOff): string
    {
        foreach ($this->config['conversion_sf_to_web']['dayoff'] as $search => $replace) {
            $dayOff = str_replace($search, $replace, $dayOff);
        }

        return $dayOff;
    }

    /**
     * 事業所名の編集
     *
     * @param string $officeName 事業所名
     * @return string
     */
    private function editOfficeName(string $officeName): string
    {
        // 事業所名の頭の【】を除去する
        // 例) 【テスト】エスエムエス【ホゲホゲ】整骨院 => エスエムエス【ホゲホゲ】整骨院
        return preg_replace('/^【.*?】/', '', $officeName);
    }

    /**
     * 募集資格の変換
     *
     * @param string $jobType
     * @return string
     */
    private function convertJobType(string $jobType): string
    {
        $jobTypeList = explode(';', $jobType);

        $mapping = $this->config['conversion_sf_to_web']['job_type'];
        foreach ($jobTypeList as $index => $jobType) {
            $jobType = mb_convert_kana($jobType, 'k', 'UTF-8');
            if (!empty($mapping[$jobType])) {
                $jobTypeList[$index] = $mapping[$jobType];
            }
        }

        return implode(",", $jobTypeList);
    }

    /**
     * 施設形態の変換
     *
     * @param string $business
     * @return string
     */
    private function convertBusiness(string $business): string
    {
        $businessList = explode(';', $business);

        $mapping = $this->config['conversion_sf_to_web']['business'];
        foreach ($businessList as $index => $business) {
            $businessList[$index] = !empty($mapping[$business]) ? $mapping[$business] : self::OTHER_BUSINESS_ID;
        }

        return implode(",", $businessList);
    }

    /**
     * 都道府県と市区町村から勤務地を生成
     *
     * @param string $addr1Name 都道府県名
     * @param string $addr2Name 市区町村
     * @return string
     */
    private function generateWorkLocation(string $addr1Name, string $addr2Name): string
    {
        if (!empty($addr2Name)) {
            return "$addr1Name $addr2Name";
        }

        return $addr1Name;
    }

    /**
     * 住所の生成
     *
     * @param string $addr1Name 都道府県名
     * @param string $addr2Name 市区町村名
     * @param string $addrStreet 町名・番地
     * @return string
     */
    private function generateAddr(string $addr1Name, string $addr2Name, string $addrStreet): string
    {
        return "$addr1Name$addr2Name$addrStreet";
    }

    /**
     * 給与の変換
     *
     * @param string $salary
     * @return string
     */
    private function convertSalary(string $salary): string
    {
        if (empty($salary)) {
            return self::SALARY_COMMON_SENTENCE;
        }

        foreach ($this->config['conversion_sf_to_web']['salary'] as $search => $replace) {
            $salary = str_replace($search, $replace, $salary);
        }

        return $salary;
    }

    /**
     * 事業所名公開フラグの生成
     *
     * @param string $accountNameFlag 事業所名公開非公開
     * @return int
     */
    private function generatePubliclyFlag(string $accountNameFlag): int
    {
        return $accountNameFlag === self::PUBLICLY_FLAG_ON_NAME ? self::FLAG_ON : self::FLAG_OFF;
    }

    /**
     * hw_flagの生成
     *
     * @param string
     * @param array
     * @return int
     */
    private function generateHwFlag(string $sfAccountId, array $gcpHwDataList): int
    {
        return array_search($sfAccountId, $gcpHwDataList) ? 1 : 0;
    }

    /**
     * 重複の除去
     *
     * @param array $order 求人情報
     * @return array
     */
    private function removeDuplicatedValues(array $order): array
    {
        $order['employment_type'] = $this->removeDuplicatedValue($order['employment_type']);
        $order['job_type'] = $this->removeDuplicatedValue($order['job_type']);

        return $order;
    }

    /**
     * 重複値を除去する
     *
     * @param string $value 雇用形態
     * @return string
     */
    private function removeDuplicatedValue(string $value): string
    {
        // 一度配列にしてから重複除去する
        $valueList = explode(';', $value);
        $valueList = array_unique($valueList);

        return implode(';', $valueList);
    }

    /**
     * Feed用のCSVをS3にアップロードする
     *
     * @param array $orders オーダー一覧
     */
    public function uploadFeedCsv(array $orders)
    {
        $header = $this->config['csv_for_feed']['header'];

        $ordersForFeed = [];
        foreach ($orders as $order) {
            foreach ($order as $key => $value) {
                // Feed CSVで不要な項目を削除する
                if (!in_array($key, $header)) {
                    unset($order[$key]);
                }
            }
            $ordersForFeed[] = $order;
        }

        // S3へのアップロード
        (new S3Manager('s3'))->uploadData(
            self::S3_PATH_FOR_FEED . self::CSV_FOR_FEED_FILE_NAME,
            $header,
            $ordersForFeed,
        );
    }

    /**
     * Web側の求人テーブルのUpsert
     *
     * @param array $orders
     * @return int Upsertした件数
     */
    public function upsertOrder(array $orders): int
    {
        // 配列のキーとWeb側のDBテーブルのカラム名のマッピング
        $mapping = $this->config['mapping_array_to_woa_opportunity'];

        $ordersForWeb = [];
        $lineNo = 0;
        foreach ($orders as $order) {
            $lineNo++;
            $orderForWeb = [];
            foreach ($order as $key => $value) {
                if (!empty($mapping[$key])) {
                    $dbColumnSizeLimit = $mapping[$key]['db_column_size_limit'];
                    // DB側の定義を超えるサイズのデータが混ざっているかを確認
                    if (mb_strlen($value) > $dbColumnSizeLimit) {
                        throw new \Exception("{$lineNo}行目の{$key}の文字の長さが上限の {$dbColumnSizeLimit} を超えています");
                    }

                    // DBのカラム名をキーとした配列を作成
                    $dbColumnName = $mapping[$key]['db_column'];
                    $orderForWeb[$dbColumnName] = $value;
                }
            }
            $ordersForWeb[] = $orderForWeb;
        }

        return $this->woaOpportunityManager->upsertFromList($orders);
    }

    /**
     * すでにwoa_opportunityに存在するデータの内、upsert対象にないレコードを論理削除する
     *
     * @param array $upsertTargetOrder
     * @return int 論理削除件数
     */
    public function softDeleteOrder(array $upsertTargetOrder): int
    {
        $upsertTargetOrder = new Collection($upsertTargetOrder);

        // 既存の全オーダーレコード
        //   ※ 削除フラグが1のレコードについては改めて論理削除する必要ないので削除フラグ0の全レコード
        $allOfOrders = $this->woaOpportunityManager->getAllRecords();

        // upsert対象になっていないレコード（論理削除対象）のみの一覧を作る
        $filtered = $allOfOrders->filter(function ($order, $key) use ($upsertTargetOrder) {
            $selected = $upsertTargetOrder->where('sf_order_id', $order['sf_order_id']);

            return $selected->isEmpty();
        });

        // 論理削除
        $softDeleteList = $filtered->map(function ($order, $key) {
            return $order;
        });
        $this->woaOpportunityManager->softDeleteList($softDeleteList->toArray());

        return count($softDeleteList);
    }
}
