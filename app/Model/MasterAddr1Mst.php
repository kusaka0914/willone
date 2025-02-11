<?php
namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class MasterAddr1Mst extends Model
{
    protected $table = 'master_addr1_mst';

    /**
     * 都道府県一覧を返す
     *
     * @return Collection
     */
    public function getList()
    {
        return $this->select('id', 'addr1', 'addr1_roma')
            ->where('delete_flag', 0)
            ->orderBy('id')
            ->get();
    }

    /**
     * 都道府県名一覧を返す
     *
     * @return Collection $result 都道府県名一覧
     */
    public function getPrefNames()
    {
        $result = $this->select('id', 'addr1')
            ->where('delete_flag', 0)
            ->orderBy('id')
            ->pluck('addr1', 'id');

        return $result;
    }

    /**
     * 都道府県を取得
     * @access public
     * @param int $id
     * @return Collection
     */
    public function getAddr1ById(int $id):  ? object
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
     * 都道府県名からID取得
     * @access public
     * @param string $addr1_name
     * @return integer $addr1
     */
    public function getAddr1idByName($addr1_name)
    {
        if (empty($addr1_name)) {
            return null;
        }

        $result = $this->select('id')
            ->where('addr1', $addr1_name)
            ->where('delete_flag', 0)
            ->first();

        return $result->id;
    }

    /**
     * 都道府県名をキーとして都道府県IDの一覧を返す
     *
     * @return \Illuminate\Database\Eloquent\Collection $result 都道府県ID一覧
     */
    public function getPrefIds()
    {
        $result = $this->select('id', 'addr1')
            ->where('delete_flag', 0)
            ->orderBy('id')
            ->pluck('id', 'addr1');

        return $result;
    }

    /**
     * エリアidをキーとして都道府県の一覧を返す
     * @access public
     * @param int $addr0_id
     * @return \Illuminate\Database\Eloquent\Collection $result 都道府県ID一覧
     */
    public function getArea(int $addr0_id) :  ? object
    {
        $query = $this->select('id', 'addr1', 'addr1_roma')
            ->where('delete_flag', 0)
            ->orderBy('id');

        if (!empty($addr0_id)) {
            $query->where('addr0_id', $addr0_id);
        }

        return $query->get();
    }

    /**
     * idをキーとして都道府県の一覧を返す
     * @access public
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection $result 都道府県ID一覧
     */
    public function getListPrefecture(array $ids = []) :  ? object
    {
        $query = $this->select('id', 'addr1', 'addr1_roma', 'addr0_id')
            ->where('delete_flag', 0)
            ->orderBy('id');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }

    /**
     * 都道府県ローマ字名から都道府県データを取得
     * @access public
     * @param string $addr1_roma
     * @return Collection
     */
    public function getAddr1ByRoma(string $addr1_roma) :  ? object
    {
        if (empty($addr1_roma)) {
            return null;
        }

        $result = $this->where('addr1_roma', $addr1_roma)
            ->where('delete_flag', 0)
            ->first();

        return $result;
    }

    /**
     * 求人のある都道府県一覧を取得
     * @access public
     * @param string $jobTypeGroup
     * @return Collection
     */
    public function getJobCountList(string $jobTypeGroup = null) :  ? object
    {
        $query = $this->leftJoin("woa_opportunity", "{$this->table}.id", "=", "woa_opportunity.addr1")
            ->leftJoin('company as co', 'co.id', '=', 'woa_opportunity.company_id')
            ->select(
                "{$this->table}.id",
                "{$this->table}.addr1 AS addr2_name",
                "{$this->table}.addr1_roma",
                DB::raw("COUNT(woa_opportunity.id) AS order_count")
            )
            ->where(function ($query) {
                $query->where('co.del_flg', 0);
                $query->orWhereNull('co.del_flg');
            })
            ->where("woa_opportunity.webpublicly_flag", 1)
            ->where("woa_opportunity.publicly_flag", 1)
            ->where("woa_opportunity.exist_order_flag", 1)
            ->where("woa_opportunity.delete_flag", 0)
            ->groupBy("{$this->table}.id")
            ->groupBy("{$this->table}.addr1")
            ->groupBy("{$this->table}.addr1_roma");

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
