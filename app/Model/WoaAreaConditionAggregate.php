<?php
namespace App\Model;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class WoaAreaConditionAggregate extends Model
{
    protected $table = "woa_area_condition_aggregate";

    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    const FLAG_ON = 1; // フラグ：ON
    const FLAG_OFF = 0; // フラグ：OFF

    /**
     * 都道府県の件数を取得
     * @param Array $params
     * @return Collection
     */
    public function getAreaData(Array $params): object
    {
        $query = DB::table('woa_area_condition_aggregate')
        ->select(
            $this->table . ".sum",
            $this->table . ".addr1",
            $this->table . ".addr2",
            'ma1m.addr1_roma',
            'ma1m.addr1 as addr1_name',
        )
        ->join('master_addr1_mst AS ma1m', function ($join) {
            $join->on('ma1m.id', '=', 'woa_area_condition_aggregate.addr1')
                ->where('ma1m.delete_flag', '=', self::FLAG_OFF);
        })
        ->where($this->table . '.delete_flag', self::FLAG_OFF)
        ->where($this->table . '.job_type', $params['job_type'])
        ->where('ma1m.addr0_id', $params['addr0_id']);
        if (!empty($params['search_key'])) {
            $query->where($this->table . '.search_key', $params['search_key']);
        }
        if (!empty($params['conditions'])) {
            $query->where($this->table . '.conditions', $params['conditions']);
        }
        $areaDataAll = $query->get();
        $areaData = $areaDataAll->filter(function ($value) {
            return $value->sum > 0;
        });
        return $areaData;
    }

    /**
     * 都道府県に紐づく一覧の取得
     * @param array $params
     * @return Collection
     */
    public function getAggregateData($params): object
    {
        $conditions = [
            'job_type' => $params['job_type'] ?? null,
            'addr1' => $params['addr1'] ?? null,
            'conditions' => $params['conditions'] ?? null,
        ];
        $query = $this->getSearchQuery($conditions);
        $query->leftjoin('master_addr2_mst AS ma2m', function ($join) {
            $join->on('ma2m.id', '=', 'woa_area_condition_aggregate.addr2')
                ->where('ma2m.delete_flag', '=', self::FLAG_OFF);
        });
        return $query->get();
    }

    /**
     * クエリの取得
     * @access private
     * @param array $conditions
     * @return builder
     */
    private function getSearchQuery(array $conditions) :  ?object
    {
        $query = DB::table('woa_area_condition_aggregate')
        ->select(
            $this->table . ".sum",
            $this->table . ".addr1",
            $this->table . ".addr2",
            $this->table . ".conditions",
            $this->table . ".value",
            $this->table . ".search_key",
            $this->table . ".job_type",
            'ma2m.addr2',
            'ma2m.addr2_roma',
        );
        $query->where($this->table . '.delete_flag', self::FLAG_OFF);
        if (!empty($conditions['job_type'])) {
            $query->where($this->table . '.job_type', $conditions['job_type']);
        }
        if (!empty($conditions['addr1'])) {
            $query->where($this->table . '.addr1', $conditions['addr1']);
        }

        if (!empty($conditions['addr2'])) {
            $query->where($this->table . '.addr2', $conditions['addr2']);
        }
        if (!empty($conditions['conditions'])) {
            $query->where($this->table . '.conditions', $conditions['conditions']);
        }

        return $query;
    }
}
