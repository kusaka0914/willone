<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Model\MasterAddr1Mst;
use App\Model\WoaAreaConditionAggregate;
use App\Model\WoaOpportunity;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 集計バッチ
 */
class AreaConditionAggregate extends Command
{

    private const ERROR = -1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AreaConditionAggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '絞り込み件数生成';

    // 引数(出力タイプ)
    private $logger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WoaOpportunity $woaOpportunity, MasterAddr1Mst $mstAddr1)
    {
        parent::__construct();

        $this->woaOpportunity = $woaOpportunity;
        $this->mstAddr1 = $mstAddr1;
    }

    /**
     * 初期化
     *
     * @return void
     */
    private function init()
    {
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 初期化
        $this->init();

        try {
            DB::beginTransaction();
            $result = $this->createAggregate();
            if ($result) {
                DB::commit();
            } else {
                DB::rollBack();
                $this->logger->error('集計テーブルの登録、更新に失敗しました。');
                $this->logger->info('処理終了');
                $this->logger->notifyErrorToSentry();

                return self::ERROR;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error(implode("\n", [
                '集計テーブル登録でエラーが発生しました。',
                $e->getMessage(),
                $e->getCode(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString(),
            ]));
            $this->logger->error('集計テーブルの登録、更新に失敗しました。');
            $this->logger->notifyErrorToSentry();
            $this->logger->info('処理終了');

            return self::ERROR;
        }

        $this->logger->info('処理終了');

        return Command::SUCCESS;
    }

    /**
     * 集計データ作成、更新
     * @return bool
     */
    private function createAggregate() :bool
    {
        $condition = [
            'delete_flag' => 0,
        ];
        $count = DB::table('woa_area_condition_aggregate')->where($condition)->count();
        // データがない場合は削除しない
        if ($count > 0) {
            // 削除件数が戻り値として格納される
            $result = WoaAreaConditionAggregate::query()->delete(); // delete_flag=1を残さないので全件物理削除してから登録する
            if (!$result) {
                return false;
            }
        }

        // 職種別新着求人一覧
        $indexSyokusyuList = config('ini.INDEX_JOB_TYPE_AGGREGATE');

        // 求人を持つ都道府県一覧の取得
        $prefDataList = $this->mstAddr1->getJobCountList();
        $now = Carbon::now()->format('Y-m-d H:i:s');
        // 職種
        foreach ($indexSyokusyuList as $syokusyuName) {
            // 都道府県
            foreach ($prefDataList as $prefData) {
                // 都道府県の件数を取得
                $prefQuery = [
                    'addr1_id'       => $prefData->id,
                    'job_type_group' => $syokusyuName,
                ];
                $cnt = $this->woaOpportunity->jobSearchCount($prefQuery);
                $insertData = [
                    'job_type'    => $syokusyuName,
                    'addr1'       => $prefData->id,
                    'addr2'       => null,
                    'conditions'  => 'pref',
                    'value'       => null,
                    'search_key'  => 'pref',
                    'sum'         => $cnt,
                    'regist_date' => $now,
                    'update_date' => $now,
                ];
                // バルクインサートでこけた場合、エラーログが大量に出るかつ、速度を求めるものでもないので一件ずつ登録
                $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                if (!$result) {
                    return false;
                }

                // 市区町村全体件数
                $stateQuery = [
                    'addr1_id'       => $prefData->id,
                    'groupby'        => 'addr2',
                    'job_type_group' => $syokusyuName,
                ];
                $cntData = $this->woaOpportunity->jobSearchAggregateCount($stateQuery);
                foreach ($cntData as $one) {
                    if ($one->addr2_count > 0) {
                        $insertData = [
                            'job_type'    => $syokusyuName,
                            'addr1'       => $prefData->id,
                            'addr2'       => $one->addr2,
                            'conditions'  => 'state',
                            'value'       => null,
                            'search_key'  => 'state',
                            'sum'         => $one->addr2_count,
                            'regist_date' => $now,
                            'update_date' => $now,
                        ];
                        $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                        if (!$result) {
                            return false;
                        }
                    }
                }
                // 雇用形態
                foreach (config('ini.EMPLOY_TYPE') as $employType) {
                    $employQuery = [
                        'addr1_id'       => $prefData->id,
                        'employ'         => $employType['query_key'],
                        'job_type_group' => $syokusyuName,
                    ];
                    // 都道府県X職種X雇用形態
                    $cntData = $this->woaOpportunity->jobSearchCount($employQuery);
                    if ($cntData > 0) {
                        $insertData = [
                            'job_type'    => $syokusyuName,
                            'addr1'       => $prefData->id,
                            'addr2'       => null,
                            'conditions'  => 'employ',
                            'value'       => $employType['value'],
                            'search_key'  => $employType['search_key'],
                            'sum'         => $cntData,
                            'regist_date' => $now,
                            'update_date' => $now,
                        ];
                        $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                        if (!$result) {
                            return false;
                        }
                    }
                    $employQuery = [
                        'addr1_id'       => $prefData->id,
                        'groupby'        => 'addr2',
                        'employ'         => $employType['query_key'],
                        'job_type_group' => $syokusyuName,
                    ];
                    // 都道府県X市区町村X職種X雇用形態
                    $cntData = $this->woaOpportunity->jobSearchAggregateCount($employQuery);
                    foreach ($cntData as $one) {
                        if ($one->addr2_count > 0) {
                            $insertData = [
                                'job_type'    => $syokusyuName,
                                'addr1'       => $prefData->id,
                                'addr2'       => $one->addr2,
                                'conditions'  => 'employ',
                                'value'       => $employType['value'],
                                'search_key'  => $employType['search_key'],
                                'sum'         => $one->addr2_count,
                                'regist_date' => $now,
                                'update_date' => $now,
                            ];
                            $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                            if (!$result) {
                                return false;
                            }
                        }
                    }
                }
                // 施設形態
                foreach (config('ini.BUSINESS_TYPE') as $business) {
                    if ($business['search_key'] == 'others') {
                        $business['query_key'] = explode(',', $business['query_key']);
                    }
                    $businessQuery = [
                        'addr1_id'       => $prefData->id,
                        'business'       => $business['query_key'],
                        'job_type_group' => $syokusyuName,
                    ];
                    // 都道府県X職種X施設形態
                    $cntData = $this->woaOpportunity->jobSearchCount($businessQuery);
                    if ($cntData > 0) {
                        $insertData = [
                            'job_type'    => $syokusyuName,
                            'addr1'       => $prefData->id,
                            'addr2'       => null,
                            'conditions'  => 'business',
                            'value'       => $business['value'],
                            'search_key'  => $business['search_key'],
                            'sum'         => $cntData,
                            'regist_date' => $now,
                            'update_date' => $now,
                        ];
                        $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                        if (!$result) {
                            return false;
                        }
                    }
                    $businessQuery = [
                        'addr1_id'       => $prefData->id,
                        'groupby'        => 'addr2',
                        'business'       => $business['query_key'],
                        'job_type_group' => $syokusyuName,
                    ];
                    $cntData = $this->woaOpportunity->jobSearchAggregateCount($businessQuery);
                    foreach ($cntData as $one) {
                        if ($one->addr2_count > 0) {
                            $insertData = [
                                'job_type'    => $syokusyuName,
                                'addr1'       => $prefData->id,
                                'addr2'       => $one->addr2,
                                'conditions'  => 'business',
                                'value'       => $business['value'],
                                'search_key'  => $business['search_key'],
                                'sum'         => $one->addr2_count,
                                'regist_date' => $now,
                                'update_date' => $now,
                            ];
                            $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                            if (!$result) {
                                return false;
                            }
                        }
                    }
                }
                // 駅ちか5分
                $stationQuery = [
                    'addr1_id'       => $prefData->id,
                    'is_ekichika5'   => true,
                    'job_type_group' => $syokusyuName,
                ];
                // 都道府県X職種X駅ちか5分
                $cntData = $this->woaOpportunity->jobSearchCount($stationQuery);
                if ($cntData > 0) {
                    $insertData = [
                        'job_type'    => $syokusyuName,
                        'addr1'       => $prefData->id,
                        'addr2'       => null,
                        'conditions'  => 'ekichika5',
                        'value'       => null,
                        'search_key'  => 'ekichika5',
                        'sum'         => $cntData,
                        'regist_date' => $now,
                        'update_date' => $now,
                    ];
                    $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                    if (!$result) {
                        return false;
                    }
                }
                $stationQuery = [
                    'addr1_id'       => $prefData->id,
                    'groupby'        => 'addr2',
                    'is_ekichika5'   => true,
                    'job_type_group' => $syokusyuName,
                ];
                $cntData = $this->woaOpportunity->jobSearchAggregateCount($stationQuery);
                foreach ($cntData as $one) {
                    if ($one->addr2_count > 0) {
                        $insertData = [
                            'job_type'    => $syokusyuName,
                            'addr1'       => $prefData->id,
                            'addr2'       => $one->addr2,
                            'conditions'  => 'ekichika5',
                            'value'       => null,
                            'search_key'  => 'ekichika5',
                            'sum'         => $one->addr2_count,
                            'regist_date' => $now,
                            'update_date' => $now,
                        ];
                        $result = DB::table('woa_area_condition_aggregate')->insert($insertData);
                        if (!$result) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }
}
