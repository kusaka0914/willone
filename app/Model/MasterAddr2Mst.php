<?php
namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class MasterAddr2Mst extends Model
{
    protected $table = 'master_addr2_mst';

    /**
     * master_addr1_mst へのリレーション設定
     *
     * @return BelongsTo
     */
    public function masterAddr1(): BelongsTo
    {
        return $this->belongsTo(MasterAddr1Mst::class, 'addr1_id', 'id')
            ->where('delete_flag', 0);
    }

    /**
     * 市区町村名一覧を返す
     *
     * @return Collection $result 市区町村名一覧
     */
    public function getCityNames()
    {
        $result = $this->select('id', 'addr2')
            ->where('delete_flag', 0)
            ->orderBy('sort')
            ->pluck('addr2', 'id');

        return $result;
    }

    /**
     * 市区町村名からID取得
     * @access public
     * @param string $addr2_name
     * @return integer $addr2
     */
    public function getAddr2idByName($addr2_name)
    {
        $addrId = null;

        if (empty($addr2_name)) {
            return $addrId;
        }

        $result = $this->select('id')
            ->where('addr2', $addr2_name)
            ->where('delete_flag', 0)
            ->first();

        if (!empty($result)) {
            $addrId = $result->id;
        }

        return $addrId;
    }

    /**
     * 市区町村を取得
     * @access public
     * @param int $id
     * @return object
     */
    public function getAddr2ById(int $id):  ? object
    {
        if (empty($id)) {
            return null;
        }
        $result = $this->where('id', $id)
            ->where('delete_flag', 0)
            ->first();

        return $result;
    }

    /**
     * master_addr2_mst.id を配列で指定し、親の master_addr1_mst も一緒に取得する
     *
     * @param array $ids
     * @return Collection
     */
    public function getAddr2WithAddr1ByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        return $this->newQuery()->with('masterAddr1')->whereIn('id', $ids)
            ->where('delete_flag', 0)
            ->get();
    }

    /**
     * 市区町村名一覧を返す
     *
     * @return \Illuminate\Database\Eloquent\Collection $result 市区町村名一覧
     */
    public function getAddr2List()
    {
        $result = $this->select('id', 'addr1_id', 'addr2')
            ->where('delete_flag', 0)
            ->orderBy('sort')
            ->get();

        return $result;
    }

    /**
     * 市区町村名一覧を返す
     * @access public
     * @param int $addr1_id
     * @return Collection
     */
    public function getAddr2ListByAddr1Id(int $addr1_id) :  ? object
    {
        $query = $this->select('id', 'addr1_id', 'addr2', 'addr2_roma')
            ->where('delete_flag', 0)
            ->orderBy('sort');

        if (!empty($addr1_id)) {
            $query->where('addr1_id', $addr1_id);
        }

        return $query->get();
    }

    /**
     * 都道府県＆市区町村のローマ字名から市区町村データを取得
     * @access public
     * @param string $addr1_roma
     * @param string $addr2_roma
     * @return Collection
     */
    public function getAddr2ByRoma(string $addr1_roma, string $addr2_roma) :  ? object
    {
        if (empty($addr1_roma) || empty($addr2_roma)) {
            return null;
        }

        $column = [
            'master_addr2_mst.addr1_id',
            'a1.addr1',
            'a1.addr1_roma',
            'master_addr2_mst.id as addr2_id',
            'master_addr2_mst.addr2',
            'master_addr2_mst.addr2_roma',
            'master_addr2_mst.lat',
            'master_addr2_mst.lng',
        ];

        $result = $this->select($column)
            ->leftJoin('master_addr1_mst as a1', 'a1.id', '=', 'master_addr2_mst.addr1_id')
            ->where('a1.addr1_roma', $addr1_roma)
            ->where('master_addr2_mst.addr2_roma', $addr2_roma)
            ->where('master_addr2_mst.delete_flag', 0)
            ->first();

        return $result;
    }

    /**
     * 指定した都道府県で求人のある市区町村一覧を取得
     *
     * @param int $addr1Id
     * @param string|null $jobTypeGroup
     * @return Collection
     */
    public function getJobCountList(int $addr1Id, string $jobTypeGroup = null): Collection
    {
        $query = $this->leftJoin("woa_opportunity", "{$this->table}.id", "=", "woa_opportunity.addr2")
            ->leftJoin('company as co', 'co.id', '=', 'woa_opportunity.company_id')
            ->select(
                "{$this->table}.id",
                "{$this->table}.addr2 AS addr2_name",
                "{$this->table}.addr2_roma",
                DB::raw("COUNT(woa_opportunity.id) AS order_count")
            )
            ->where(function ($query) {
                $query->where('co.del_flg', 0);
                $query->orWhereNull('co.del_flg');
            })
            ->where("woa_opportunity.webpublicly_flag", 1)
            ->where("woa_opportunity.exist_order_flag", 1)
            ->where("woa_opportunity.delete_flag", 0)
            ->where("woa_opportunity.addr1", $addr1Id)
            ->groupBy("{$this->table}.id")
            ->groupBy("{$this->table}.addr2")
            ->groupBy("{$this->table}.addr2_roma");

        // 職種絞り込み
        if (!empty($jobTypeGroup)) {
            $jobType = config("ini.JOB_TYPE_GROUP.{$jobTypeGroup}.ids");
            $query->where(function ($query) use ($jobType) {
                foreach ($jobType as $key => $jobTypeId) {
                    if ($key == 0) {
                        $query->where(DB::raw("FIND_IN_SET('{$jobTypeId}', woa_opportunity.job_type)"), ">", 0);
                    } else {
                        $query->orWhere(DB::raw("FIND_IN_SET('{$jobTypeId}', woa_opportunity.job_type)"), ">", 0);
                    }
                }
            });
        }

        return $query->get();
    }
}
