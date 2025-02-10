<?php
namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class WoaOpportunity extends Model
{
    protected $table = "woa_opportunity";

    protected $guarded = ['id'];

    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    const FLAG_ON = 1; // フラグ：ON
    const FLAG_OFF = 0; // フラグ：OFF

    /**
     * 求人一覧の取得
     * @access public
     * @return Collection
     */
    public function getJobIdList()
    {
        $query = $this->getSearchQuery([]);

        $query->orderByRaw("{$this->table}.publicly_flag = 0 asc")
            ->orderby("{$this->table}.last_confirmed_datetime", 'desc');
        $col = [
            $this->table . ".job_id",
            $this->table . ".last_confirmed_datetime",
        ];

        return $query->get($col);
    }

    /**
     * 求人情報取得
     * @access public
     * @param int $id
     * @return object
     */
    public function getOrderInfoByJobId(int $id): ?object
    {
        // job_idが空、数値でなかったら空返却
        if (empty($id) || !preg_match("/^[0-9]+$/", $id)) {
            return new stdClass();
        }

        $result = $this->where([
            ['job_id', '=', $id],
            ['webpublicly_flag', '=', self::FLAG_ON],
            ['delete_flag', '=', self::FLAG_OFF],
        ])->first();

        // 事業所名を返却
        return $result;
    }

    /**
     * エリア毎かつ、職種タイプ毎で、求人数を取得するメソッド
     * @access public
     * @param integer $pref 都道府県id
     * @param array $types 職種タイプ
     * @return integer
     */
    public function getAreaJobCount(int $pref, array $types): int
    {
        $query = $this->where('delete_flag', 0);

        if (!empty($pref)) {
            $query = $query->where('addr1', $pref);
        }

        if (!empty($types)) {
            $query = $query->where(
                function ($query_sub) use ($types) {
                    foreach ($types as $type) {
                        $query_sub->orWhereRaw('FIND_IN_SET(?, job_type)', $type);
                    }
                }
            );
        }

        $count = $query->count();

        return $count;
    }

    /**
     * woa_opportunity.company_id の更新
     * @access public
     * @param string $sfOrderId ID
     * @param int $companyId SF連携フラグ
     * @return integer $result 更新結果
     */
    public function updateCompanyId(string $sfOrderId, int $companyId): int
    {
        $columns = [
            'company_id' => $companyId,
        ];

        $result = $this
            ->where('sf_order_id', $sfOrderId)
            ->where('delete_flag', self::FLAG_OFF)
            ->update($columns);

        return $result;
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
    public function newjob(int $pref = null, int $state = null, string $type = null, array $freeword): ?object
    {
        $columns = [
            $this->table . ".*",
            DB::raw(
                "(CASE WHEN
                    (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, {$this->table}.employ) AND metm.delete_flag = 0) IS NULL THEN ''
                ELSE (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, {$this->table}.employ) AND metm.delete_flag = 0)
                END
                ) AS employment_type"
            ),
            DB::raw(
                "(
                CASE WHEN
                    (SELECT group_concat(mjtm.job_type separator ',') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, {$this->table}.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                ELSE ( SELECT group_concat(mjtm.job_type separator ',') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, {$this->table}.job_type) AND mjtm.delete_flag = 0)
                END
                ) AS job_type_name"
            ),
            'ma1m.addr1 as addr1_name',
            'ma2m.addr2 as addr2_name',
        ];
        $query = $this->select($columns)
            ->join('master_addr1_mst as ma1m', function ($join) {
                $join->on('ma1m.id', '=', $this->table . '.addr1')
                    ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
            })
            ->join('master_addr2_mst as ma2m', function ($join) {
                $join->on([
                    ['ma2m.id', '=', $this->table . '.addr2'],
                ])->where('ma2m.delete_flag', '=', self::FLAG_OFF);
            })
            ->where($this->table . '.delete_flag', self::FLAG_OFF)
            ->where($this->table . '.webpublicly_flag', self::FLAG_ON)
            ->where($this->table . '.exist_order_flag', self::FLAG_ON)
            ->orderby($this->table . '.last_confirmed_datetime', 'desc')
            ->orderby($this->table . '.id', 'desc')
            ->take(4);

        // 都道府県絞り込み
        if (!empty($pref)) {
            $query->where($this->table . '.addr1', $pref);
        }

        // 市区町村絞り込み
        if (!empty($state)) {
            $query->where($this->table . '.addr2', $state);
        }

        // 職種絞り込み
        if (!empty($type)) {
            $query->whereNotNull(DB::raw("FIND_IN_SET('{$type}', {$this->table}.job_type)"));
        }

        // 職種絞り込み
        if (!empty($type)) {
            $jobType = config("ini.JOB_TYPE_GROUP.{$type}.ids");
            if (!empty($jobType)) {
                $query->where(function ($query) use ($jobType) {
                    foreach ($jobType as $key => $jobTypeId) {
                        if ($key == 0) {
                            $query->where(DB::raw("FIND_IN_SET('{$jobTypeId}', {$this->table}.job_type)"), ">", 0);
                        } else {
                            $query->orWhere(DB::raw("FIND_IN_SET('{$jobTypeId}', {$this->table}.job_type)"), ">", 0);
                        }
                    }
                });
            }
        }

        // フリーワード絞り込み
        foreach ($freeword as $value) {
            $query->where(DB::raw("CONCAT_WS('@', {$this->table}.office_name, {$this->table}.business)"), 'like', "%$value%");
        }

        return $query->get();
    }

    /**
     * 求人一覧取得
     * @access public
     * @param array $conditions
     * @return Collection
     */
    public function jobSearch(array $conditions): Collection
    {
        $query = $this->getSearchQuery($conditions);

        // join(addr1/addr2)
        $query->join('master_addr1_mst as ma1m', function ($join) {
            $join->on('ma1m.id', '=', $this->table . '.addr1')
                ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
        })
            ->join('master_addr2_mst as ma2m', function ($join) {
                $join->on([
                    ['ma2m.id', '=', $this->table . '.addr2'],
                ])->where('ma2m.delete_flag', '=', self::FLAG_OFF);
            });

        // ソート
        $query->orderby($this->table . '.last_confirmed_datetime', 'desc')
            ->orderby($this->table . '.id', 'desc');

        // 取得するカラム
        $columns = [
            $this->table . ".*",
            DB::raw(
                "(CASE WHEN
                    (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, {$this->table}.employ) AND metm.delete_flag = 0) IS NULL THEN ''
                ELSE (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, {$this->table}.employ) AND metm.delete_flag = 0)
                END
                ) AS employment_type"
            ),
            DB::raw(
                "(
                CASE WHEN
                    (SELECT group_concat(mjtm.job_type separator ',') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, {$this->table}.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                ELSE ( SELECT group_concat(mjtm.job_type separator ',') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, {$this->table}.job_type) AND mjtm.delete_flag = 0)
                END
                ) AS job_type_name"
            ),
            'ma1m.addr1 as addr1_name',
            'ma2m.addr2 as addr2_name',
        ];

        // limit
        if (Arr::exists($conditions, 'limit')) {
            $query->limit($conditions['limit']);
        }

        // offset
        if (Arr::exists($conditions, 'offset')) {
            $query->offset($conditions['offset']);
        }

        return $query->get($columns);
    }

    /**
     * 求人一覧件数取得
     * @access public
     * @param array $conditions
     * @return int
     */
    public function jobSearchCount(array $conditions): ?int
    {
        $query = $this->getSearchQuery($conditions);

        return $query->count();
    }

    /**
     * 求人一覧件数取得
     * @access public
     * @param array $conditions
     * @return object
     */
    public function jobSearchAggregateCount(array $conditions): ?object
    {
        $query = $this->getSearchQuery($conditions)->select([
            $this->table . '.addr2',
            \DB::raw('count(*) as addr2_count'),
        ]);
        if (!empty($conditions['groupby'])) {
            $query->groupBy($this->table . '.' . $conditions['groupby']);
        }

        return $query->get();
    }

    /**
     * 絞り込み条件リストの取得
     * @access public
     * @param int $addr1
     * @return Collection
     */
    public function getFilteringListByAddr1(int $addr1): ?object
    {
        $conditions = [
            'addr1_id' => $addr1,
        ];
        $query = $this->getSearchQuery($conditions);

        $columns = [
            'job_id',
            'job_type',
        ];

        return $query->get($columns);
    }

    /**
     * 件数の取得
     * @access public
     * @param array $condition
     * @return int
     */
    public function getFilteringListCount(array $conditions): ?int
    {
        $conditions = [
            'addr1_id' => $addr1,
        ];
        $query = $this->getSearchQuery($conditions);

        $columns = [
            'job_id',
            'job_type',
        ];

        return $query->get($columns);
    }

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
        $result = $this->select(
            "{$this->table}.job_id",
            "{$this->table}.inq_number",
            "{$this->table}.detail",
            "{$this->table}.salary",
            "{$this->table}.worktime",
            "{$this->table}.closest",
            "{$this->table}.dayoff",
            "{$this->table}.office_name",
            "{$this->table}.company_name",
            "{$this->table}.hp_url",
            "{$this->table}.business",
            "{$this->table}.worklocation",
            "{$this->table}.postal_code",
            "{$this->table}.addr1",
            "{$this->table}.addr2",
            "{$this->table}.addr",
            "{$this->table}.g_latitude",
            "{$this->table}.g_longitude",
            "{$this->table}.employ",
            "{$this->table}.station1",
            "{$this->table}.station2",
            "{$this->table}.station3",
            "{$this->table}.minutes_walk1",
            "{$this->table}.minutes_walk2",
            "{$this->table}.minutes_walk3",
            "{$this->table}.exist_order_flag",
            "{$this->table}.publicly_flag",
            "{$this->table}.update_date",
            "{$this->table}.job_type",
            "{$this->table}.order_pr_title",
            'ma1m.addr1_roma',
            'ma2m.addr2_roma',
            'ma1m.addr1 as addr1_name',
            'ma2m.addr2 as addr2_name',
            "{$this->table}.company_id",
            "{$this->table}.human_resource",
            "{$this->table}.last_confirmed_datetime",
            DB::raw(
                "(CASE WHEN
                    (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, {$this->table}.employ) AND metm.delete_flag = 0) IS NULL THEN ''
                    ELSE (SELECT group_concat(metm.emp_type separator '/') FROM master_emp_type_mst AS metm WHERE FIND_IN_SET(metm.id, {$this->table}.employ) AND metm.delete_flag = 0)
                    END
                    ) AS employment_type"
            ),
            DB::raw(
                "(CASE WHEN
                    (SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, {$this->table}.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                    ELSE ( SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, {$this->table}.job_type) AND mjtm.delete_flag = 0)
                    END
                    ) AS job_type_name"
            )
        )
            ->join('master_addr1_mst as ma1m', function ($join) {
                $join->on('ma1m.id', '=', "{$this->table}.addr1")
                    ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
            })
            ->join('master_addr2_mst as ma2m', function ($join) {
                $join->on([
                    ['ma2m.id', '=', "{$this->table}.addr2"],
                ])->where('ma2m.delete_flag', '=', self::FLAG_OFF);
            })
            ->where([
                ["{$this->table}.job_id", '=', $id],
                ["{$this->table}.webpublicly_flag", '=', self::FLAG_ON],
                ["{$this->table}.delete_flag", '=', self::FLAG_OFF],
            ])->first();

        return $result;
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
        $query = $this->getSearchQuery($conditions)
            ->select([
                $this->table . '.addr2',
                \DB::raw('count(*) as addr2_count'),
            ])
            ->groupBy($this->table . '.addr2')
            ->orderByRaw('addr2_count desc, ' . $this->table . '.addr2 asc');

        if (!empty($limit)) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * 指定条件の求人を配列で取得
     * @access public
     * @param array $conditions
     * @return Collection
     */
    public function getJobList(array $conditions): Collection
    {
        $query = $this->getSearchQuery($conditions);

        // 募集職種名取得
        $query->select('*', DB::raw(
            "(CASE WHEN
                (SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, $this->table.job_type) AND mjtm.delete_flag = 0) IS NULL THEN ''
                ELSE ( SELECT group_concat(mjtm.job_type separator '/') FROM master_job_type_mst AS mjtm WHERE FIND_IN_SET(mjtm.id, $this->table.job_type) AND mjtm.delete_flag = 0)
                END
            ) AS job_type_name"
        ));

        // オーダーPRタイトルがないものは除外
        $query->whereNotNull('order_pr_title')
            ->where('order_pr_title', '<>', '');

        return $query->get();
    }

    /**
     * 全求人取得
     * @return Collection
     */
    public function getAllData()
    {
        $query = $this->getSearchQuery([]);

        return $query->get();
    }

    /**
     * 求人一覧クエリの取得
     * @access private
     * @param array $conditions
     * @return builder
     */
    private function getSearchQuery(array $conditions): ?object
    {
        $query = $this->leftJoin('company as co', 'co.id', '=', $this->table . '.company_id')
            ->where(function ($query) {
                $query->where('co.del_flg', 0);
                $query->orWhereNull('co.del_flg');
            })
            ->where($this->table . '.delete_flag', 0)
            ->where($this->table . '.webpublicly_flag', 1)
            ->where($this->table . '.exist_order_flag', 1);

        // 都道府県絞り込み
        if (Arr::exists($conditions, 'addr1_id')) {
            $query->where($this->table . '.addr1', $conditions['addr1_id']);
        }

        // 市区町村絞り込み
        if (Arr::exists($conditions, 'addr2_id')) {
            $query->where($this->table . '.addr2', $conditions['addr2_id']);
        }
        if (Arr::exists($conditions, 'addr2_id_list')) {
            $query->whereIn($this->table . '.addr2', $conditions['addr2_id_list']);
        }
        // 1,2　の形で持ってるのでlike検索
        if (!empty($conditions['employ'])) {
            $query->where($this->table . '.employ', 'LIKE', '%' . $conditions['employ'] . '%');
        }
        if (!empty($conditions['business'])) {
            if (is_array($conditions['business'])) {
                $query->whereIn($this->table . '.business', $conditions['business']);
            } else {
                $query->where($this->table . '.business', $conditions['business']);
            }
        }

        // 職種絞り込み
        if (Arr::exists($conditions, 'job_type_group')) {
            $jobType = config("ini.JOB_TYPE_GROUP.{$conditions['job_type_group']}.ids");
            $query->where(function ($query) use ($jobType) {
                foreach ($jobType as $key => $jobTypeId) {
                    if ($key == 0) {
                        $query->where(DB::raw("FIND_IN_SET('{$jobTypeId}', {$this->table}.job_type)"), ">", 0);
                    } else {
                        $query->orWhere(DB::raw("FIND_IN_SET('{$jobTypeId}', {$this->table}.job_type)"), ">", 0);
                    }
                }
            });
        }

        // 運営会社絞り込み
        if (Arr::exists($conditions, 'company_id')) {
            $query->where($this->table . '.company_id', $conditions['company_id']);
        }

        // 公開フラグ
        if (Arr::exists($conditions, 'publicly_flag')) {
            $query->where($this->table . '.publicly_flag', $conditions['publicly_flag']);
        }

        // 駅から徒歩5分以内 (is_ekichika5: キー存在 かつ true をチェック)
        if (!empty($conditions['is_ekichika5'])) {
            // NOTE: station1 空文字で minutes_walk1 0の場合あり
            $query->where($this->table . '.station1', '<>', '')
                ->whereBetween($this->table . '.minutes_walk1', [0, 5]);
        }

        // PR対象フラグ絞り込み
        if (Arr::exists($conditions, 'pr_flag')) {
            $query->where($this->table . '.pr_flag', $conditions['pr_flag']);
        }

        // フリーワード絞り込み
        if (Arr::exists($conditions, 'freeword')) {
            foreach ($conditions['freeword'] as $value) {
                $query->where(DB::raw("CONCAT_WS('@', office_name, business)"), 'like', "%{$value}%");
            }
        }

        // 指定求人IDを除外
        if (Arr::exists($conditions, 'exclude_job_ids')) {
            $query->whereNotIn($this->table . '.job_id', $conditions['exclude_job_ids']);
        }

        // 掲載日時 範囲指定
        if (Arr::exists($conditions, 'last_confirmed_datetime_start') && is_array($conditions['last_confirmed_datetime_end'])) {
            $query->whereBetween($this->table . '.last_confirmed_datetime', [
                $conditions['last_confirmed_datetime_start'],
                $conditions['last_confirmed_datetime_end'],
            ]);
        }

        // 掲載日時
        if (Arr::exists($conditions, 'last_confirmed_datetime')) {
            $query->where($this->table . '.last_confirmed_datetime', $conditions['last_confirmed_datetime']);
        }

        // 並び順
        if (Arr::exists($conditions, 'order_by_desc')) {
            $query->orderBy($conditions['order_by_desc'], 'desc');
        }

        // limit
        if (Arr::exists($conditions, 'limit')) {
            $query->limit($conditions['limit']);
        }

        return $query;
    }

    /**
     * 旧テーブルと新テーブルの紐づけ
     * @access public
     * @param Integer $id
     * @return Collection
     */
    public function wolinkingData($id)
    {
        // 旧jobテーブルのjob.nameからwoaOpportunity.office_nameを紐つける為のデータを取得
        $jobName = config('redirect.WO_LINK_DATA_JOB_TABLE')[$id] ?? null;
        if (empty($jobName)) {
            return false;
        }
        // 名前でしか紐づけないので名前で紐づけ
        $result = $this->select('job_id')
            ->where('delete_flag', 0)
            ->where('webpublicly_flag', 1)
            ->where('exist_order_flag', 1)
            ->where('office_name', $jobName)
            ->get();

        return $result;
    }

    /**
     * 注目枠求人を全件取得
     *
     * @return Collection
     */
    public function findPrOpportunity(): Collection
    {
        return WoaOpportunity::where('pr_flag', self::FLAG_ON)
            ->where('delete_flag', self::FLAG_OFF)
            ->get();
    }

    /**
     * 注目枠求人を取得
     *
     * @param int $id
     * @return self
     */
    public function findPrOpportunityById(int $id): ?self
    {
        return WoaOpportunity::where('pr_flag', self::FLAG_ON)
            ->where('delete_flag', self::FLAG_OFF)
            ->where('id', $id)
            ->first();
    }

    /**
     * コメディカルオーダーIDから求人情報を取得
     *
     * @param string $sfOrderId
     * @return self
     */
    public function findBySfOrderId(string $sfOrderId): ?self
    {
        return WoaOpportunity::where('sf_order_id', $sfOrderId)
            ->where('delete_flag', self::FLAG_OFF)
            ->first();
    }
}
