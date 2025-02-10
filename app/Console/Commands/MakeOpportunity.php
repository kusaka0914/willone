<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\ImportOpportunityManager;
use App\Managers\WoaOpportunityManager;
use App\Managers\S3Manager;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\WoaOpportunity;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class MakeOpportunity extends Command
{
    // 近隣オーダーの距離(メートル)
    private const FIRSTDISTNCE = 15000;
    private const SECONDDISTANCE = 30000;

    private const TYPE2_FIRST = 1;
    private const TYPE2_SECOND = 2;
    private const TYPE2_THIRD = 3;

    private const ORDER_LIMIT = 20;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MakeOpportunity {iniFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ターゲットDB用CSVデータの生成';

    /**
     * @var ImportOpportunityManager
     */
    private $importOpportunityManager;

    /**
     * @var S3Manager
     */
    private $s3Manager;

    /**
     * @var BatchLogger
     */
    private $logger;

    /**
     * @var WoaOpportunity
     */
    private $woaOpportunity;

    /**
     * @var array
     */
    private array $config;

    /**
     * @var array
     */
    private array $types;

    /**
     * @var string
     */
    private string $s3Path;

    /**
     * @var array
     */
    private array $header;

    /**
     * @var string
     */
    private string $csvFilePath;

    /**
     * @var string
     */
    private string $lastConfirmedDay;

    /**
     * @var string
     */
    private string $lastConfirmedDayStart;

    /**
     * @var string
     */
    private string $lastConfirmedDayEnd;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

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
        if (empty($iniFilePath) || empty($types)) {
            throw new \Exception("{$className}: iniファイル設定値読込み失敗: {$iniFilePath}");
        }

        $this->config = config("batch.import_opportunity");
        $this->types = $types;
        $this->s3Path = $importFilePath;
        $this->csvFilePath = storage_path('app/' . $csvFileName);
        $this->header = $header;

        // 今日,新着(30日以内), 継続(7~90日前)の日付取得
        $dt = Carbon::now();
        $this->lastConfirmedDay = $dt->subDay(30)->format('Y-m-d H:i:s');
        $this->lastConfirmedDayStart = $dt->subDay(7)->format('Y-m-d H:i:s');
        $this->lastConfirmedDayEnd = $dt->subDay(90)->format('Y-m-d H:i:s');

        // メモリサイズ変更
        ini_set('memory_limit', $this->config["memory_limit"]);

        $this->importOpportunityManager = new ImportOpportunityManager($this->logger);
        $this->s3Manager = new S3Manager('s3');
        $this->woaOpportunity = new WoaOpportunity();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): mixed
    {
        $this->init();

        $this->logger->info('処理開始');

        // Web（Search）とFeed用に加工された求人一覧を取得
        $this->logger->info('ターゲットDB用CSVデータの生成開始');
        // 登録、更新対象データ取得
        $orderForWebAndFeed = $this->importOpportunityManager->getTargetDbData();

        if (empty($orderForWebAndFeed)) {
            $this->logger->info('対象データが0件のため処理を終了します');
            $this->logger->info('処理正常終了');

            return Command::SUCCESS;
        }

        $createCsvResult = $this->createCsv($orderForWebAndFeed);
        if ($createCsvResult === false) {
            $this->logger->error('ターゲットDB用CSVデータの生成に失敗しました');
            return Command::FAILURE;
        }

        $orderForWebAndFeedCount = $orderForWebAndFeed ? count($orderForWebAndFeed) : 0;

        $this->logger->info("{$orderForWebAndFeedCount}件を取得しました");
        try {
            $this->s3Manager->uploadFile($this->s3Path, $this->csvFilePath);
        } catch (\Exception $e) {
            $this->logger->error("ターゲットDB用CSVデータの生成に失敗しました。" . $e->getMessage());
            return Command::FAILURE;
        }
        $this->logger->info('ターゲットDBCSVデータの生成処理終了');
        return Command::SUCCESS;
    }

    /**
     * CSV書き込み
     * @param resource $fp
     * @param array $data
     * @param int $csvCount
     * @return void
     */
    private function writeCsv($fp, array $data, int $csvCount)
    {
        // 並びをヘッダーに合わせる nullの場合は空文字を入れる
        $line = [];
        foreach ($this->header as $headerKey) {
            $line[$headerKey] = $data[$csvCount][$headerKey] ?? '';
        }
        fputcsv($fp, $line);
    }

    /**
     * ターゲットDBに追加するデータを生成
     * @param array $data
     * @return boolean
     */
    private function createCsv(array $data): bool
    {
        $fp = fopen($this->csvFilePath, 'w');
        // ヘッダ書込み
        fputcsv($fp, $this->header);

        $addrOne = (new MasterAddr1Mst)->getArea(0);
        $addr2 = (new MasterAddr2Mst)->getAddr2List();

        // 前レコードとの比較用
        $beforeAddr1Id = null;
        $beforeAddr2Id = null;
        $beforeJobType = null;
        try {
            foreach ($data as $csvCount => $csvOne) {
                $jobType = $this->getJobType(config('ini.JOB_TYPE_GROUP'), $csvOne);
                // 職業、分割グループが取得できない場合は空行を生成
                if (empty($jobType) || empty($csvOne['分割グループ'])) {
                    $data[$csvCount] = $this->makeEmptyCsvData($this->types['continuation'], $data[$csvCount]);
                    $data[$csvCount] = $this->makeEmptyCsvData($this->types['new'], $data[$csvCount]);
                    $data[$csvCount] = $this->makeEmptyCsvData($this->types['municipality_order'], $data[$csvCount]);
                    $data[$csvCount] = $this->makeEmptyCsvData($this->types['annual_holiday_order'], $data[$csvCount]);
                    $data[$csvCount] = $this->makeEmptyCsvData($this->types['neighborhood_order'], $data[$csvCount]);
                    $this->writeCsv($fp, $data, $csvCount);
                    continue;
                }

                $addr1Id = $addrOne->where('addr1', $csvOne['都道府県'])->first()->id;
                $addr2Data = $addr2->where('addr2', $csvOne['市区町村'])->first();

                // 前レコードと同じ場合はデータ取得しない
                if ($beforeAddr1Id != $addr1Id || $beforeJobType != $jobType) {
                    $addr1Opportunity = $this->woaOpportunity->getJobList(['addr1_id' => $addr1Id, 'job_type_group' => $jobType]);
                    $beforeAddr1Id = $addr1Id;
                }

                if (!empty($addr2Data->id) && ($beforeAddr2Id != $addr2Data->id || $beforeJobType != $jobType)) {
                    $addr2Opportunity = $this->woaOpportunity->getJobList(['addr2_id' => $addr2Data->id, 'job_type_group' => $jobType]);
                    $beforeAddr2Id = $addr2Data->id;
                } else {
                    $addr2Opportunity = collect([]);
                }

                // 求人データの重複チェック用
                $data[$csvCount]['selectIds'] = [];

                // 継続
                $data[$csvCount] = $this->addContinuationOpportunityData($data[$csvCount], $addr1Opportunity);
                // 新着
                $data[$csvCount] = $this->addNewOpportunityData($data[$csvCount], $addr1Opportunity);
                // 市区町村オーダー
                $data[$csvCount] = $this->addAddr2OpportunityData($data[$csvCount], $addr1Opportunity, $addr2Opportunity);
                // 年収オーダー
                // $data[$csvCount] = $this->addAnnualSalaryOpportunityData($data[$csvCount], $addr1Opportunity);
                // 年間休日オーダー
                $data[$csvCount] = $this->addAnnualHolidayOpportunityData($data[$csvCount], $addr1Opportunity);
                // 近隣オーダー
                $data[$csvCount] = $this->addNeighborhoodOpportunityData($data[$csvCount], $addr2Data, $addr1Opportunity);

                // データ出力されないよう削除
                unset($data[$csvCount]['selectIds']);
                $this->writeCsv($fp, $data, $csvCount);
            }
        } catch (\Exception $e) {
            $this->logger->error("ターゲットDB用CSVデータの生成に失敗しました。" . $e->getMessage());
            return false;
        } finally {
            fclose($fp);
        }

        return true;
    }

    /**
     * CSVデータの空行の生成
     * @param string $typeOne 事業所タイプ
     * @param array $csvOne 求職者データ
     * @return array
     */
    private function makeEmptyCsvData(string $typeOne, array $csvOne): array
    {
        $csvOne[$typeOne . '_オススメ掲載No'] = '';
        $csvOne[$typeOne . '_都道府県'] = '';
        if ($typeOne == $this->types['new']) {
            $csvOne[$typeOne . '_経過日数'] = '';
        } elseif ($typeOne == $this->types['municipality_order']) {
            // 市区町村オーダーの場合は市区町村を追加
            $csvOne[$typeOne . '_市区町村'] = '';
        } elseif ($typeOne == $this->types['annual_salary_order']) {
            // 年収オーダーの場合は年収を追加
            $csvOne[$typeOne . '_年収'] = '';
        } elseif ($typeOne == $this->types['annual_holiday_order']) {
            // 年間休日オーダーの場合は年間休日を追加
            $csvOne[$typeOne . '_年間休日'] = '';
        }

        $csvOne[$typeOne . '_LPURL'] = '';
        $csvOne[$typeOne . '_オーダータイプ'] = '';
        $csvOne[$typeOne . '_事業所名'] = '';
        $csvOne[$typeOne . '_おすすめポイント'] = '';
        $csvOne[$typeOne . '_募集職種'] = '';
        $csvOne[$typeOne . '_雇用形態'] = '';
        $csvOne[$typeOne . '_施設'] = '';
        $csvOne[$typeOne . '_最寄駅'] = '';
        $csvOne[$typeOne . '_お問い合わせ番号'] = '';

        return $csvOne;
    }

    /**
     * CSVデータの生成
     * @param array $data 事業所のオブジェクト
     * @param string $typeOne 事業所タイプ
     * @param int $typeTwo 事業所掲載順位タイプ
     * @param array $csvOne 求職者データ
     * @return array
     */
    private function makeCsvData(array $data, string $typeOne, int $typeTwo, array $csvOne): array
    {
        if (empty($data)) {
            return $csvOne;
        }

        $csvOne[$typeOne . '_オススメ掲載No'] = $data['job_id'];
        if ($data['publicly_flag'] == 0) {
            $data['office_name'] = '事業所名非公開';
        }
        $station = '';
        if ($data['station1']) {
            $station = $data['station1'] . '駅';
            if ($data['station2']) {
                $station .= '/' . $data['station2'] . '駅';
            }
            if ($data['station3']) {
                $station .= '/' . $data['station3'] . '駅';
            }
        }

        $csvOne[$typeOne . '_都道府県'] = $csvOne['都道府県'];
        // 新着の場合は経過日数を追加 (現在日 - last_confirmed_datetime)
        if ($typeOne == $this->types['new']) {
            $lastConfirmedDay = Carbon::parse($data['last_confirmed_datetime']);
            $now = Carbon::now();
            $csvOne[$typeOne . '_経過日数'] = $now->diffInDays($lastConfirmedDay);
        } elseif ($typeOne == $this->types['municipality_order']) {
            // 市区町村オーダーの場合は市区町村を追加
            $csvOne[$typeOne . '_市区町村'] = $csvOne['市区町村'];
        } elseif ($typeOne == $this->types['annual_salary_order']) {
            // 年収オーダーの場合は年収を追加
            $csvOne[$typeOne . '_年収'] = $data['annual_income_max'];
        } elseif ($typeOne == $this->types['annual_holiday_order']) {
            // 年間休日オーダーの場合は年間休日を追加
            $csvOne[$typeOne . '_年間休日'] = $data['nenkankyuujitsu'];
        }

        $csvOne[$typeOne . '_LPURL'] = 'https://www.jinzaibank.com/woa/detail/' . $data['job_id'] . '.html';
        $csvOne[$typeOne . '_オーダータイプ'] = $typeTwo;
        $csvOne[$typeOne . '_事業所名'] = $data['office_name'];
        $csvOne[$typeOne . '_おすすめポイント'] = $data['order_pr_title'];
        $csvOne[$typeOne . '_募集職種'] = $data['job_type_name'];
        $csvOne[$typeOne . '_雇用形態'] = $data['employment_type'];
        $csvOne[$typeOne . '_施設'] = $data['business'];
        $csvOne[$typeOne . '_最寄駅'] = $station;
        $csvOne[$typeOne . '_お問い合わせ番号'] = $data['inq_number'];

        // 求人重複チェック用に求人IDを追加
        $csvOne['selectIds'][] = $data['job_id'];

        return $csvOne;
    }

    /**
     * 職業の判定
     * @param array $jobType 職業配列
     * @param array $one CSVデータ
     * @return string
     */
    private function getJobType(array $jobType, array $one): string
    {
        foreach ($jobType as $job => $jobData) {
            $result = preg_match('/' . $jobData['name'] . '/', $one['保有資格']);
            if ($result) {
                return $job;
            } elseif ($jobData['name'] == $one['保有資格']) {
                return $job;
            }
        }
        return '';
    }

    /**
     * 継続求人データの取得
     * @param array $csvOne
     * @param Collection $addr1Opportunity
     * @param string $type タイプ名
     * @return array
     */
    private function addContinuationOpportunityData(array $csvOne, Collection $addr1Opportunity, string $type = ''): array
    {
        // 他タイプで突合がない場合は継続データを取得するため元のタイプ名を設定
        $typeName = ($type != '') ? $type : $this->types['continuation'];
        // 求職者にマッチする継続データの取得 他タイプからのデータ取得の場合はタイプ2を3に設定 7~90日前
        $conditions = [
            'exclude_job_ids' => $csvOne['selectIds'],
            'last_confirmed_datetime_start' => $this->lastConfirmedDayStart,
            'last_confirmed_datetime_end' => $this->lastConfirmedDayEnd
        ];
        // 年間休日の場合は1以上のデータを出力したい
        if ($typeName == $this->types['annual_holiday_order']) {
            $conditions = ['nenkankyuujitsu' => 1];
        }

        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);

        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $typeName, ($type) ? self::TYPE2_THIRD : self::TYPE2_FIRST, $csvOne);
        }

        // 日付の指定を削除
        unset($conditions['last_confirmed_datetime_start']);
        unset($conditions['last_confirmed_datetime_end']);
        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);

        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $typeName, ($type) ? self::TYPE2_THIRD : self::TYPE2_SECOND, $csvOne);
        }
        // 都道府県でない場合はからとする
        return $this->makeEmptyCsvData($typeName, $csvOne);
    }

    /**
     * 新着求人データの取得
     * @param array $csvOne
     * @param Collection $addr1Opportunity
     * @return array
     */
    private function addNewOpportunityData(array $csvOne, Collection $addr1Opportunity): array
    {
        // 求職者にマッチするデータの取得 掲載から30日以内
        $conditions = [
            'exclude_job_ids' => $csvOne['selectIds'],
            'last_confirmed_datetime' => $this->lastConfirmedDay,
        ];

        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);
        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['new'], self::TYPE2_FIRST, $csvOne);
        }

        // 新着がない場合は継続データを取得
        return $this->addContinuationOpportunityData($csvOne, $addr1Opportunity, $this->types['new']);
    }

    /**
     * 市区町村求人データの取得
     * @param array $csvOne
     * @param Collection $addr1Opportunity
     * @param Collection $addr2Opportunity
     * @return array
     */
    private function addAddr2OpportunityData(array $csvOne, Collection $addr1Opportunity, Collection $addr2Opportunity): array
    {
        if ($addr2Opportunity->isNotEmpty()) {
            // 求職者にマッチするデータの取得 7~90日前
            $conditions = [
                'exclude_job_ids' => $csvOne['selectIds'],
                'last_confirmed_datetime_start' => $this->lastConfirmedDayStart,
                'last_confirmed_datetime_end' => $this->lastConfirmedDayEnd,
            ];

            $tmpOpportunityData = $this->getOpportunityData($addr2Opportunity, $conditions);
            if (!empty($tmpOpportunityData)) {
                return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['municipality_order'], self::TYPE2_FIRST, $csvOne);
            }

            unset($conditions['last_confirmed_datetime_start']);
            unset($conditions['last_confirmed_datetime_end']);
            $tmpOpportunityData = $this->getOpportunityData($addr2Opportunity, $conditions);

            if (!empty($tmpOpportunityData)) {
                return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['municipality_order'], self::TYPE2_SECOND, $csvOne);
            }
        }

        // 市区町村オーダーがない場合は継続データを取得
        return $this->addContinuationOpportunityData($csvOne, $addr1Opportunity, $this->types['municipality_order']);
    }

    /**
     * 年収求人データの取得
     * @param array $csvOne
     * @param Collection $addr1Opportunity
     * @return array
     */
    private function addAnnualSalaryOpportunityData(array $csvOne, Collection $addr1Opportunity): array
    {
        // 求職者にマッチするデータの取得 7~90日前
        $conditions = [
            'exclude_job_ids' => $csvOne['selectIds'],
            'last_confirmed_datetime_start' => $this->lastConfirmedDayStart,
            'last_confirmed_datetime_end' => $this->lastConfirmedDayEnd,
            'order_by_desc' => 'annual_income_max',
            'limit' => self::ORDER_LIMIT
        ];

        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);
        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['annual_salary_order'], self::TYPE2_FIRST, $csvOne);
        }

        // 日付の指定を削除
        unset($conditions['last_confirmed_datetime_start']);
        unset($conditions['last_confirmed_datetime_end']);
        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);

        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['annual_salary_order'], self::TYPE2_SECOND, $csvOne);
        }

        // 日付の指定なしでも取得できなかった場合は継続データを取得
        return $this->addContinuationOpportunityData($csvOne, $addr1Opportunity, $this->types['annual_salary_order']);
    }

    /**
     * 年間休日求人データの取得
     * @param array $csvOne
     * @param Collection $addr1Opportunity
     * @return array
     */
    private function addAnnualHolidayOpportunityData(array $csvOne, Collection $addr1Opportunity): array
    {
        // 求職者にマッチするデータの取得 7~90日前
        $conditions = [
            'exclude_job_ids' => $csvOne['selectIds'],
            'last_confirmed_datetime_start' => $this->lastConfirmedDayStart,
            'last_confirmed_datetime_end' => $this->lastConfirmedDayEnd,
            'nenkankyuujitsu' => '1', // 指定した値以上の年間休日数の求人を取得
            'order_by_desc' => 'nenkankyuujitsu',
            'limit' => self::ORDER_LIMIT
        ];

        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);
        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['annual_holiday_order'], self::TYPE2_FIRST, $csvOne);
        }

        // 日付の指定を削除
        unset($conditions['last_confirmed_datetime_start']);
        unset($conditions['last_confirmed_datetime_end']);
        $tmpOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);
        if (!empty($tmpOpportunityData)) {
            return $this->makeCsvData($tmpOpportunityData[array_rand($tmpOpportunityData)], $this->types['annual_holiday_order'], self::TYPE2_SECOND, $csvOne);
        }

        // 日付の指定なしでも取得できなかった場合は継続データを取得
        return $this->addContinuationOpportunityData($csvOne, $addr1Opportunity, $this->types['annual_holiday_order']);
    }

    /**
     * 近隣の求人データの取得
     * @param array $csvOne
     * @param MasterAddr2Mst|null $addr2
     * @param Collection $addr1Opportunity
     * @return array
     */
    private function addNeighborhoodOpportunityData(array $csvOne, ?MasterAddr2Mst $addr2, Collection $addr1Opportunity): array
    {
        // 求職者からaddr2のデータが取得できていない場合は継続データを取得
        if (empty($addr2)) {
            return $this->addContinuationOpportunityData($csvOne, $addr1Opportunity, $this->types['neighborhood_order']);
        }


        // 求職者の市区町村から座標取得
        $userAddressData = $addr2->where('addr2', $csvOne['市区町村'])->first();

        $woaOpportunityMgr = new WoaOpportunityManager();
        $firstTargetOpportunityData = [];
        $secondTargetOpportunityData = [];

        // 求職者にマッチするデータの取得 7~90日前
        $conditions = [
            'exclude_job_ids' => $csvOne['selectIds'],
            'last_confirmed_datetime_start' => $this->lastConfirmedDayStart,
            'last_confirmed_datetime_end' => $this->lastConfirmedDayEnd,
        ];

        $targetOpportunityData = $this->getOpportunityData($addr1Opportunity, $conditions);

        foreach ($targetOpportunityData as $OpportunityData) {
            // 求職者と求人の距離を取得
            $distanceToOrder = $woaOpportunityMgr->getDistanceFromKyusyokusyaToOrder($userAddressData, $OpportunityData);
            // 指定距離圏内の求人を取得
            if ($distanceToOrder <= self::FIRSTDISTNCE) {
                $firstTargetOpportunityData[] = $OpportunityData;
            } elseif ($distanceToOrder <= self::SECONDDISTANCE) {
                $secondTargetOpportunityData[] = $OpportunityData;
            }
        }

        // 指定距離圏内の求人がある場合
        if (!empty($firstTargetOpportunityData)) {
            return $this->makeCsvData($firstTargetOpportunityData[array_rand($firstTargetOpportunityData)], $this->types['neighborhood_order'], self::TYPE2_FIRST, $csvOne);
        }
        if (!empty($secondTargetOpportunityData)) {
            return $this->makeCsvData($secondTargetOpportunityData[array_rand($secondTargetOpportunityData)], $this->types['neighborhood_order'], self::TYPE2_SECOND, $csvOne);
        }

        // 指定距離圏内の求人がない場合は継続データを取得
        return $this->addContinuationOpportunityData($csvOne, $addr1Opportunity, $this->types['neighborhood_order']);
    }

    /**
     * opportunityデータから条件にあったデータを取得
     * @param Collection $opportunityData
     * @param array $conditions
     * @return array
     */
    private function getOpportunityData(Collection $opportunityData, array $conditions): array
    {
        $tmpOpportunityData = $opportunityData;

        if (Arr::exists($conditions, 'exclude_job_ids')) {
            $tmpOpportunityData = $tmpOpportunityData->whereNotIn('job_id', $conditions['exclude_job_ids']);
        }

        if (Arr::exists($conditions, 'last_confirmed_datetime')) {
            $tmpOpportunityData = $tmpOpportunityData->where('last_confirmed_datetime', '>=', $conditions['last_confirmed_datetime']);
        }

        if (Arr::exists($conditions, 'last_confirmed_datetime_start') && Arr::exists($conditions, 'last_confirmed_datetime_end')) {
            $tmpOpportunityData = $tmpOpportunityData->whereBetween('last_confirmed_datetime', [$conditions['last_confirmed_datetime_end'], $conditions['last_confirmed_datetime_start']]);
        }

        if (Arr::exists($conditions, 'nenkankyuujitsu')) {
            // 年間休日がnull or 空白の場合は除外
            $tmpOpportunityData = $tmpOpportunityData->where('nenkankyuujitsu', '>', $conditions['nenkankyuujitsu']);
        }

        if (Arr::exists($conditions, 'order_by_desc')) {
            $tmpOpportunityData = $tmpOpportunityData->sortByDesc($conditions['order_by_desc']);
        }

        if (Arr::exists($conditions, 'limit')) {
            $tmpOpportunityData = $tmpOpportunityData->take($conditions['limit']);
        }

        return $tmpOpportunityData->toArray();
    }
}
