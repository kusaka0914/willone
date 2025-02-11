<?php
/**
 *  WoaOpportunityManager
 */

namespace App\Managers;

use App\Model\WoaCustomer;
use App\Model\WoaOpportunity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WoaOpportunityManager
{
    const FLAG_ON = 1; // フラグ：ON
    const FLAG_OFF = 0; // フラグ：OFF
    // 特定の事業所の場合、画像を変更する。
    const IMAGE_CHANGE_LIST = [
        1225269 => 2,
    ];

    /**
     * idを指定して仕事・職種情報を取得する
     *
     * job_idをキーに、仕事・職種情報を取得する。
     *
     * @access public
     * @param int $id ジョブID
     * @return Collection 仕事・職種情報
     */
    public function getWoaOpportunityByJobId(int $id): ?object
    {
        $woaOpportunity = new WoaOpportunity;
        $result = $woaOpportunity->getWoaOpportunityByJobId($id);

        if (!$result) {
            return null;
        }

        if ($result->publicly_flag == self::FLAG_OFF) {
            $result = $this->convertPrivate($result);
        }

        return $result;
    }

    /**
     * idを指定して仕事・職種情報を取得する
     * job_idをキーに、仕事・職種情報を取得する。(feedの絞り込みの条件が別タスクで未決のため、一旦切り分ける)
     *
     * @access public
     * @param int $id ジョブID
     * @return Collection 仕事・職種情報
     */
    public function getJobDetailByJobId(int $id): ?object
    {
        $result = $this->getWoaOpportunityByJobId($id);

        if (!$result) {
            return null;
        }

        // feedとの条件の違い(feedの絞り込みの条件が別タスクで未決のため、一旦切り分ける)
        if ($result->exist_order_flag == self::FLAG_OFF) {
            return null;
        }

        return $result;
    }

    /**
     * $prefSearchFlagがnullの場合、10キロ以内のオーダーを取得
     * $prefSearchFlagがnullではない場合、求職者と同じ都道府県のオーダーを取得
     *
     * @param object $sfCustomer sf求職者情報
     * @param  integer $limit オーダー取得数
     * @param  bool $prefSearchFlag 都道府県検索判定
     * @param  integer $SearchRange オーダー検索範囲
     * @return array||bool 求人情報
     */
    public function getNearOfficeByCustomer($sfCustomer, $limit, $prefSearchFlag = false, $addr1 = null, $searchRange = 10)
    {
        if (empty($sfCustomer)) {
            return null;
        }

        // パラメータ設定
        $longitude = $sfCustomer->longitude;
        $latitude = $sfCustomer->latitude;

        // $prefSearchFlagで取得条件を変更
        if (empty($prefSearchFlag)) {
            // 10キロ以内の求人を取得するよう設定
            $selectRaw = '(sqrt(pow((? - woa.g_latitude) * 111.26, 2) + pow((? - woa.g_longitude) * 91.16, 2)) <=' . " $searchRange" . ') AS distance';
            $where = [
                ['woa.publicly_flag', '=', self::FLAG_ON],
                ['woa.delete_flag', '=', self::FLAG_OFF],
            ];
            $orderBy = 'distance';
        } else {
            // 求職者と同じ都道府県のオーダーを取得するよう設定
            $selectRaw = '(ABS(woa.g_latitude - ?) + ABS(woa.g_longitude - ?)) AS calc';
            $where = [
                ['woa.addr1', '=', $addr1],
                ['woa.publicly_flag', '=', self::FLAG_ON],
                ['woa.delete_flag', '=', self::FLAG_OFF],
            ];
            $orderBy = 'calc';
        }

        $result = DB::table('woa_opportunity as woa')
            ->select(
                'woa.job_id',
                'woa.sf_order_id',
                'woa.inq_number',
                'woa.detail',
                'woa.salary',
                'woa.worktime',
                'woa.closest',
                'woa.dayoff',
                'woa.office_name',
                'woa.company_name',
                'woa.hp_url',
                'woa.business',
                'woa.worklocation',
                'woa.postal_code',
                'woa.addr1',
                'woa.addr2',
                'woa.addr',
                'woa.g_latitude',
                'woa.g_longitude',
                'woa.employ',
                'woa.station1',
                'woa.station2',
                'woa.station3',
                'woa.exist_order_flag',
                'woa.publicly_flag',
                'woa.order_pr_title',
                'woa.update_date',
                DB::raw(
                    "(CASE WHEN
                  (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, woa.employ) AND metm.delete_flag = 0) IS NULL THEN ''
                  ELSE (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, woa.employ) AND metm.delete_flag = 0)
                  END
                ) AS employment_type"
                ),
                DB::raw(
                    "(CASE WHEN
                  (SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, woa.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                  ELSE ( SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, woa.job_type) AND mjtm.delete_flag = 0)
                  END
                ) AS job_type_name"
                ),
                'woa.job_type',
                'ma1m.addr1_roma',
                'ma1m.addr1 as addr1_name',
                'ma2m.addr2 as addr2_name',
            )
            ->selectRaw($selectRaw, [$latitude, $longitude])
            ->join('master_addr1_mst AS ma1m', function ($join) {
                $join->on('ma1m.id', '=', 'woa.addr1')
                    ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
            })
            ->join('master_addr2_mst AS ma2m', function ($join) {
                $join->on('ma2m.id', '=', 'woa.addr2')
                    ->where('ma2m.delete_flag', '=', self::FLAG_OFF);
            })
            ->where($where)
            ->orderBy($orderBy, 'asc')
            ->limit($limit)
            ->get()
            ->toArray();

        return $result ? $result : false;
    }

    /**
     * 引数の近隣の事業所取得
     * 緯度・経度、都道府県・市区町村での検索
     * @param  [type]  $job [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    public function getNearOffice($job, $limit = 10)
    {
        if (empty($job)) {
            return null;
        }

        // パラメータ設定
        $keys['job_id'] = $job->job_id;
        $keys['g_latitude'] = $job->g_latitude;
        $keys['g_longitude'] = $job->g_longitude;
        $keys['addr1_id'] = $job->addr1;
        $keys['addr2_id'] = $job->addr2;

        // 都道府県・市区町村でオーダー取得
        $result = $this->searchNearMainOfficeByAddr($keys, $limit);
        $tmpLimit = count($result);

        // LIMIT分取得できなかったら都道府県で検索してみる
        if ($tmpLimit < $limit) {
            // 市区町村のキーを削除して検索する
            unset($keys['addr2_id']);

            // 都道府県での検索は、市区町村分の件数を引いた分で取得
            $prefLimit = $limit - $tmpLimit;
            $prefResult = $this->searchNearMainOfficeByAddr($keys, $prefLimit);

            $result = array_merge($result, $prefResult);
        }
        // オブジェクトに戻して返却

        return $this->setArrayToObject($result);
    }

    /**
     * [searchNearMainOfficeByAddr description]
     * @param  [type]  $keys  [description]
     * @param  integer $limit [description]
     * @return [type]  近隣オーダーの配列 [description]
     */
    public function searchNearMainOfficeByAddr($keys, $limit = 10)
    {
        if (empty($keys)) {
            return [];
        }
        // 都道府県・市区町村で検索
        $tmpResult = $this->searchNearOfficeByAddr($keys);

        // 毎回オーダー情報がかぶらないようにランダムに選定

        return $this->getRandomValues($tmpResult, $limit);
    }

    /**
     * 都道府県、市区町村による検索
     * @param  [type] $searchParams [description]
     * @param  [type] $limit        [description]
     * @return [type]               [description]
     */
    public function searchNearOfficeByAddr($searchParams)
    {
        // where句設定
        $where = [
            ['woa.job_id', '<>', $searchParams['job_id']],
            ['woa.addr1', '=', $searchParams['addr1_id']],
            ['woa.webpublicly_flag', '=', self::FLAG_ON],
            ['woa.delete_flag', '=', self::FLAG_OFF],
            ['woa.exist_order_flag', '=', self::FLAG_ON],
        ];
        // 市区町村が指定されていたらwhere句に指定する
        if (isset($searchParams['addr2_id']) && !empty($searchParams['addr2_id'])) {
            $where[] = ['woa.addr2', '=', $searchParams['addr2_id']];
        }
        $result = DB::table('woa_opportunity as woa')
            ->select(
                'woa.job_id',
                'woa.inq_number',
                'woa.detail',
                'woa.salary',
                'woa.worktime',
                'woa.closest',
                'woa.dayoff',
                'woa.office_name',
                'woa.company_name',
                'woa.hp_url',
                'woa.business',
                'woa.worklocation',
                'woa.postal_code',
                'woa.addr1',
                'woa.addr2',
                'woa.addr',
                'woa.g_latitude',
                'woa.g_longitude',
                'woa.employ',
                'woa.station1',
                'woa.station2',
                'woa.station3',
                'woa.exist_order_flag',
                'woa.publicly_flag',
                'woa.update_date',
                'woa.last_confirmed_datetime',
                DB::raw(
                    "(CASE WHEN
                  (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, woa.employ) AND metm.delete_flag = 0) IS NULL THEN ''
                  ELSE (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, woa.employ) AND metm.delete_flag = 0)
                  END
                ) AS employment_type"
                ),
                DB::raw(
                    "(CASE WHEN
                  (SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, woa.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                  ELSE ( SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, woa.job_type) AND mjtm.delete_flag = 0)
                  END
                ) AS job_type_name"
                ),
                'woa.job_type',
                'ma1m.addr1_roma',
                'ma1m.addr1 as addr1_name',
                'ma2m.addr2 as addr2_name'
            )
            ->join('master_addr1_mst as ma1m', function ($join) {
                $join->on('ma1m.id', '=', 'woa.addr1')
                    ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
            })
            ->join('master_addr2_mst as ma2m', function ($join) {
                $join->on([
                    ['ma2m.id', '=', 'woa.addr2'],
                ])->where('ma2m.delete_flag', '=', self::FLAG_OFF);
            })
            ->where($where)
            ->get();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 配列から指定された件数分ランダムで要素を取得する
     *
     * @access private
     * @param array $values
     * @param string $keyName
     * @param integer $getCount
     * @return array
     */
    private function getRandomValues($values, $getCount)
    {
        if (empty($values) || empty($getCount)) {
            return null;
        }

        // オブジェクトを一旦配列にしてランダムで取得する
        $valuesLists = [];
        foreach ($values as $value) {
            $valuesLists[] = $value;
        }

        if (count($valuesLists) <= $getCount) {
            // 配列が取得件数以下の場合はランダムにせずそのまま返す
            return $valuesLists;
        }
        // ランダムでキーを取得する
        $randKeys = array_rand($valuesLists, $getCount);
        $result = [];
        if (is_array($randKeys)) {
            $cnt = 0;
            // ランダム取得したキーで要素を設定
            foreach ($randKeys as $randKey) {
                $result[] = $valuesLists[$randKey];
                $cnt++;
                // 指定件数到達したらおわり
                if ($cnt == $getCount) {
                    break;
                }
            }
        } else {
            $result[] = $valuesLists[$randKeys];
        }

        return $result;
    }

    /**
     * 配列をオブジェクトへ変換
     *
     * @access private
     * @param array $lists
     * @return object
     */
    private function setArrayToObject($lists)
    {
        $result = [];
        foreach ($lists as $list) {
            $object = new \stdClass;
            foreach ($list as $key => $val) {
                $object->$key = $val;
            }
            $result[] = $object;
        }

        return $result;
    }

    /**
     * 職種と市区町村（都道府県）とマッチする求人取得
     * @param  array  $keys パラメータ情報
     * @param  integer $limit 最大取得件数
     * @return object 求人情報
     */
    public function getMatchLicenseNearOrder($keys, $limit = 10)
    {
        if (empty($keys)) {
            return null;
        }

        // 都道府県・市区町村と募集職種でオーダー取得
        $result = $this->fetchMatchLicenseNearOrder($keys, $limit);
        $tmpLimit = count($result);

        // LIMIT分取得できなかったら都道府県で検索してみる
        if ($tmpLimit < $limit) {
            // 市区町村のキーを削除して検索する
            unset($keys['addr2_id']);

            // 都道府県での検索は、市区町村分の件数を引いた分で取得
            $prefLimit = $limit - $tmpLimit;
            $prefResult = $this->fetchMatchLicenseNearOrder($keys, $prefLimit);

            $result = array_merge($result, $prefResult);
        }

        // オブジェクトに戻して返却

        return $this->setArrayToObject($result);
    }

    /**
     * 都道府県・市区町村と募集職種で求人検索
     * @param  array $searchParams 検索パラメータ
     * @return 求人情報
     */
    public function fetchMatchLicenseNearOrder($searchParams, $limit = 10)
    {
        if (empty($searchParams)) {
            return [];
        }

        // where句設定
        $where = [
            ['woa.webpublicly_flag', '=', self::FLAG_ON],
            ['woa.delete_flag', '=', self::FLAG_OFF],
            ['woa.exist_order_flag', '=', self::FLAG_ON],
        ];
        // ★都道府県(編集用)が設定されていたらwhere句に指定する
        if (isset($searchParams['addr1_id']) && !empty($searchParams['addr1_id'])) {
            $where[] = ['woa.addr1', '=', $searchParams['addr1_id']];
        }
        // 保有資格(編集用)が設定されていたらwhere句に指定する
        if (isset($searchParams['job_type']) && !empty($searchParams['job_type'])) {
            $where[] = ['woa.job_type', 'rlike', $searchParams['job_type']];
        }
        // 市区町村が指定されていたらwhere句に指定する
        if (isset($searchParams['addr2_id']) && !empty($searchParams['addr2_id'])) {
            $where[] = ['woa.addr2', '=', $searchParams['addr2_id']];
        }
        $result = DB::table('woa_opportunity as woa')
            ->select(
                'woa.job_id',
                'woa.sf_order_id',
                'woa.inq_number',
                'woa.detail',
                'woa.salary',
                'woa.worktime',
                'woa.closest',
                'woa.dayoff',
                'woa.office_name',
                'woa.company_name',
                'woa.hp_url',
                'woa.business',
                'woa.worklocation',
                'woa.postal_code',
                'woa.addr1',
                'woa.addr2',
                'woa.addr',
                'woa.g_latitude',
                'woa.g_longitude',
                'woa.employ',
                'woa.station1',
                'woa.station2',
                'woa.station3',
                'woa.exist_order_flag',
                'woa.publicly_flag',
                'woa.order_pr_title',
                'woa.update_date',
                DB::raw(
                    "(CASE WHEN
                  (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, woa.employ) AND metm.delete_flag = 0) IS NULL THEN ''
                  ELSE (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, woa.employ) AND metm.delete_flag = 0)
                  END
                ) AS employment_type"
                ),
                DB::raw(
                    "(CASE WHEN
                  (SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, woa.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                  ELSE ( SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, woa.job_type) AND mjtm.delete_flag = 0)
                  END
                ) AS job_type_name"
                ),
                'woa.job_type',
                'ma1m.addr1_roma',
                'ma1m.addr1 as addr1_name',
                'ma2m.addr2 as addr2_name'
            )
            ->join('master_addr1_mst as ma1m', function ($join) {
                $join->on('ma1m.id', '=', 'woa.addr1')
                    ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
            })
            ->join('master_addr2_mst as ma2m', function ($join) {
                $join->on([
                    ['ma2m.id', '=', 'woa.addr2'],
                ])->where('ma2m.delete_flag', '=', self::FLAG_OFF);
            })
            ->where($where)
            ->get();

        if ($result) {
            $tmpResult = $result;
        } else {
            $tmpResult = false;
        }

        // 毎回オーダー情報がかぶらないようにランダムに選定

        return $this->getRandomValues($tmpResult, $limit);
    }

    /**
     * 駅情報設定
     * @param  object $job 求人情報
     * @return string $station 最寄り駅
     */
    public function setStations($job)
    {
        $stationList = [];
        // 最寄駅情報を設定
        if (!empty($job->station1)) {
            array_push($stationList, $job->station1 . '駅');
        }
        if (!empty($job->station2)) {
            array_push($stationList, $job->station2 . '駅');
        }
        if (!empty($job->station3)) {
            array_push($stationList, $job->station3 . '駅');
        }

        $station = implode('/', $stationList);

        return $station;
    }

    /**
     * オーダー一覧をUpsertする
     *
     * @param array $list オーダー一覧
     * @return int Upsertした件数
     */
    public function upsertFromList(array $list): int
    {
        $upsertCount = 0;

        foreach ($list as $record) {
            $record['delete_date'] = null;
            $record['delete_flag'] = self::FLAG_OFF;

            // updateOrCreate はレコードが存在しない場合はInsert、
            // レコードが存在し全項目を確認して差分がある場合のみUpdateする
            $wasUpsert = WoaOpportunity::updateOrCreate(
                ['sf_order_id' => $record['sf_order_id']], // キー
                $record // 更新値
            )->wasChanged();

            // Upsertされたかどうか
            if ($wasUpsert) {
                $upsertCount++;
            }
        }

        return $upsertCount;
    }

    /**
     * 全ての sf_order_idを取得
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRecords(): Collection
    {
        return WoaOpportunity::where('delete_flag', 0)
            ->select(['sf_order_id'])
            ->get();
    }

    /**
     * 対象のオーダー一覧を論理削除する
     *
     * @param array $list オーダー一覧
     */
    public function softDeleteList(array $list)
    {
        foreach ($list as $record) {
            $selected = WoaOpportunity::where('sf_order_id', $record['sf_order_id'])->first();
            $selected['delete_date'] = Carbon::now()->format("Y-m-d H:i:s");
            $selected['delete_flag'] = WoaOpportunity::FLAG_ON;
            $selected->save();
        }
    }

    /**
     * 画像ファイル名の取得
     * @access public
     * @param int $id
     * @param int $companyId
     * @return string
     */
    public function getJobImage(int $id, ?int $companyId): string
    {
        if (empty($id) || !preg_match("/^[0-9]+$/", $id)) {
            return "";
        }

        $path = "/woa/images/job/";
        if (!empty($companyId)) {
            // 指定された事業所の画像の場合、返却
            if (isset(config('ini.OPPORTUNITY_IMAGES')[$companyId])) {
                return $path . config('ini.OPPORTUNITY_IMAGES')[$companyId];
            }
        }
        $fileNum = self::IMAGE_CHANGE_LIST[$id] ?? substr($id, -1);
        $jobImageList = config('ini.JOB_IMAGE_LIST');

        $path .= $jobImageList[$fileNum];
        return $path;
    }

    /**
     * 画像ファイル名の取得
     * @access public
     * @param int $id
     * @return Collection
     */
    public function getJobListByCompanyId(int $id): ?object
    {
        $woaOpportunity = new WoaOpportunity;
        $conditions = [
            'company_id'    => $id,
            'publicly_flag' => self::FLAG_ON,
        ];
        $jobList = $woaOpportunity->jobSearch($conditions);

        if (!empty($jobList)) {
            // 画像ファイル名の取得
            foreach ($jobList as $key => $job) {
                $jobList[$key]->job_image = $this->getJobImage($job->job_id, $job->company_id);
            }
        }

        return $jobList;
    }

    /**
     * 職種別のリストを取得
     * @access public
     * @param int $addr1 都道府県ID
     * @return Collection
     */
    public function getJobTypeList(int $addr1): ?collection
    {
        $result = [];

        $woaOpportunity = new WoaOpportunity;
        $jobList = $woaOpportunity->getFilteringListByAddr1($addr1);
        if (!$jobList) {
            return null;
        }

        $jobTypeGroup = config('ini.JOB_TYPE_GROUP');
        foreach ($jobTypeGroup as $key => $jobType) {
            $item = new Collection;
            $item->type_name = $jobType['name'];
            $item->image = $jobType['image'];
            $item->order_cnt = 0;
            foreach ($jobList as $job) {
                if (empty($job->job_type)) {
                    continue;
                }
                $data = explode(",", $job->job_type);
                // job.job_type に iniに定義した job_type_groupのjob_typeの存在確認
                $exists = array_intersect($jobType['ids'], $data);
                if (count($exists) > 0) {
                    $item->order_cnt++;
                }
            }
            if ($item->order_cnt > 0) {
                $result[$key] = $item;
            }
        }

        if (count($result) == 0) {
            return null;
        }

        return new Collection($result);
    }

    /**
     * 新着求人一覧取得
     * @access public
     * @param int $pref
     * @param int $state
     * @param string $type
     * @param array $freeword
     * @return Collection
     */
    public function newjob(int $pref = null, int $state = null, string $type = null, array $freeword = []): ?object
    {
        $woaOpportunity = new WoaOpportunity;
        $result = $woaOpportunity->newjob($pref, $state, $type, $freeword);

        if (!empty($result)) {
            foreach ($result as $key => $val) {
                // 非公開求人の場合
                if ($val->publicly_flag == self::FLAG_OFF) {
                    $result[$key] = $this->convertPrivate($val);
                }
            }
        }

        return $result;
    }

    /**
     * 求人一覧取得
     * @access public
     * @param array $conditions
     * @return Collection
     */
    public function jobSearch(array $conditions): ?object
    {
        $woaOpportunity = new WoaOpportunity;
        $result = $woaOpportunity->jobSearch($conditions);

        foreach ($result as $key => $val) {
            // 非公開求人の場合
            if ($val->publicly_flag == self::FLAG_OFF) {
                $result[$key] = $this->convertPrivate($val);
            }
        }

        return $result;
    }

    /**
     * PR求人取得
     * @access public
     * @param array $conditions
     * @return Collection
     */
    public function prJobSearch(array $conditions): ?object
    {
        // ページネーションに関係なく3件取得するためoffsetを削除
        unset($conditions['offset']);

        $pr_coditions = [
            'pr_flag'   => self::FLAG_ON,
            'limit'     => 3,
        ];

        $woaOpportunity = new WoaOpportunity;
        $result = $woaOpportunity->jobSearch(array_merge($conditions, $pr_coditions));

        foreach ($result as $key => $val) {
            // 非公開求人の場合
            if ($val->publicly_flag == self::FLAG_OFF) {
                $result[$key] = $this->convertPrivate($val);
            }
        }

        return $result;
    }

    /**
     * 有効な掲載求人数取得
     *
     * @return int
     */
    public function countActiveWoaOpportunity(): int
    {
        return (new WoaOpportunity())->jobSearchCount([]);
    }

    /**
     * master_addr2_mst.id 毎の有効な掲載求人数取得
     *
     * @param array $conditions
     * @param int|null $limit
     * @return Collection
     */
    public function getAddr2CountOpportunityList(array $conditions, ?int $limit): Collection
    {
        // Eloquent Collection を Support Collection に変換
        return collect(
            (new WoaOpportunity())->getAddr2CountOpportunityList($conditions, $limit)->toArray()
        );
    }

    /**
     * 非公開処理
     * @access private
     * @param object $job
     * @return Collection
     */
    private function convertPrivate(object $job): ?object
    {
        // 店舗名を非公開にする
        $job->office_name = "【事業所名非公開】";

        // 会社名を非公開にする
        if (!empty($job->company_name)) {
            $job->company_name = "非公開";
        }

        // 勤務地を 都道府県＋市区町村にする
        if (!empty($job->addr) && !empty($job->addr1_name)) {
            $job->addr = $job->addr1_name;
            if (!empty($job->addr2_name)) {
                $job->addr .= $job->addr2_name;
            }
        }

        return $job;
    }

    /**
     * 日本測地系→世界測地系へ変換(GoogleMapは世界測地系で計算されるため)
     * @access private
     * @param int $tmpLat 緯度
     * @param int $tmpLng 経度
     * @return array
     */
    public function setToWgs(?int $tmpLat, ?int $tmpLng): array
    {
        $result = [];
        if (empty($tmpLat) || empty($tmpLng)) {
            return $result;
        }
        $tmpLatitude = bcdiv($tmpLat, (1000.0 * 3600.0), 8);
        $tmpLongitude = bcdiv($tmpLng, (1000.0 * 3600.0), 8);

        // 日本測地系→世界測地系へ変換(GoogleMapは世界測地系で計算されるため)
        $latitude = $tmpLatitude - 0.00010695 * $tmpLatitude + 0.000017464 * $tmpLongitude + 0.0046017;
        $longitude = $tmpLongitude - 0.000046038 * $tmpLatitude - 0.000083043 * $tmpLongitude + 0.010040;

        $result['lat'] = $latitude;
        $result['lng'] = $longitude;

        return $result;
    }

    /**
     * 求職者からオーダーまでの距離を算出
     * 緯度経度の1度あたりの距離は、測地場所によって異なるが、東京(経度35)をベースとして概算値で 緯度1度 = 111.26(km) , 経度1度 = 91.16(km) とする
     * @param WoaCustomer $userAddressData
     * @param WoaOpportunity $job
     * @return float|null
     */
    public function getDistanceFromKyusyokusyaToOrder(?object $userAddressData, ?array $job): ?float
    {
        // 位置情報に不備がある場合はnullを返す
        if (empty($job['g_latitude']) || empty($job['g_longitude']) || empty($userAddressData->lat) || empty($userAddressData->lng)) {
            return null;
        }

        $result = $this->setToWgs($job['g_latitude'], $job['g_longitude']);
        if (empty($result)) {
            return null;
        }
        return sqrt(pow(($result['lat'] - $result['lng']) * 111.26, 2) + pow(($userAddressData->lat - $userAddressData->lng) * 91.16, 2));
    }
}
