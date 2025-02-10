<?php
/**
 *  AreaManager
 */

namespace App\Managers;

use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AreaManager
{
    private $masterAddr1Mst;
    private $masterAddr2Mst;

    public function __construct()
    {
        $this->masterAddr1Mst = new MasterAddr1Mst();
        $this->masterAddr2Mst = new MasterAddr2Mst();
    }

    /**
     * 都道府県レコードを取得
     * @access public
     * @param integer $addr1
     * @return array / null
     * @throw \SQLException
     */
    public function getListAddr1ById($addr1)
    {
        if (empty($addr1)) {
            return null;
        }

        $sql = "
            SELECT *
            FROM master_addr1_mst
            WHERE id = ? AND delete_flag = 0
        ";
        $param = [$addr1];

        $rst = DB::select($sql, $param);

        return $rst;
    }

    /**
     * 市区町村レコードを取得
     * @access public
     * @param integer $addr2
     * @return array / null
     * @throw \SQLException
     */
    public function getListAddr2ById($addr2)
    {
        if (empty($addr2)) {
            return null;
        }

        $sql = "
            SELECT *
            FROM master_addr2_mst
            WHERE id = ? AND delete_flag = 0
        ";
        $param = [$addr2];

        $rst = DB::select($sql, $param);

        return $rst;
    }

    /**
     * 都道府県一覧の取得（エリアごとにグループ化）ParameterMaster.phpからの移植
     * @access public
     * @return Collection
     */
    public function getAreaPrefList():  ? collection
    {
        $prefectureList = $this->masterAddr1Mst->getListPrefecture();
        $areaList = config('ini.AREA_LIST');

        $result = [];
        foreach ($prefectureList as $pref) {
            $prefectures = [];
            $addr0_id = $pref->addr0_id;
            $areaName = $areaList[$addr0_id];
            foreach ($prefectureList as $value) {
                if ($addr0_id != $value->addr0_id) {
                continue;
            }
                $item = new Collection;
                $item->name = $value->addr1;
                $item->roma = $value->addr1_roma;
                $prefectures[] = $item;
            }
            $prefItem = new Collection;
            $prefItem->name = $areaName;
            $prefItem->prefectures = new Collection($prefectures);
            $result[$addr0_id] = $prefItem;
        }

        if (count($result) == 0) {
            return null;
        }

        return new Collection($result);
    }

    /**
     * 都道府県一覧の取得
     *
     * @return Collection
     */
    public function getListPrefecture(): collection
    {
        return $this->masterAddr1Mst->getListPrefecture();
    }

    /**
     * master_addr2_mst.id を配列で指定し、親の master_addr1_mst も一緒に取得する
     *
     * @param array $addr2Ids
     * @return Collection
     */
    public function getAddr2WithAddr1ByIds(array $addr2Ids): Collection
    {
        return $this->masterAddr2Mst->getAddr2WithAddr1ByIds($addr2Ids);
    }

    /**
     * 近隣市区町村取得(RJBから移植)
     * @see https://github.com/bm-sms/jinzaibank-ptotst-web/blob/78c879061ea9530bae5b9e4891bc1f2119fd37d7/app/Managers/PublicRecruitSearchManager.php#L1049-L1069
     *
     * @param int $stateId
     * @return array
     */
    public function findNearCities(int $stateId): array
    {
        $sql = <<<SQL
SELECT
area_near.*,
addr1.addr1_roma
FROM master_area_near_mst area_near
INNER JOIN master_addr1_mst addr1 ON area_near.apply_prefecture_id = addr1.id
WHERE area_near.city_id = :city_id AND area_near.delete_flag = 0 AND addr1.delete_flag = 0
ORDER BY area_near.apply_prefecture_id, area_near.apply_city_id
SQL;

        return DB::select($sql, [':city_id' => $stateId]);
    }
}
