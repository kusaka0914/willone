<?php

declare(strict_types=1);

namespace App\UseCases\Searches;

use App\Managers\AreaManager;
use App\Managers\WoaOpportunityManager;
use Illuminate\Support\Collection;

class MakeJobSearchLinksUseCase
{
    private $areaManager;
    private $woaOpportunityManager;

    private const ADDR2_LINK_LIMIT = 5;

    public function __construct()
    {
        $this->areaManager = new AreaManager();
        $this->woaOpportunityManager = new WoaOpportunityManager();
    }

    /**
     * @param string|null $jobTypeRoma
     * @param int $prefId
     * @param int|null $stateId
     * @param array $arrFreeword
     * @return array
     */
    final public function __invoke(
        ?string $jobTypeRoma,
        int $prefId,
        ?int $stateId,
        array $arrFreeword
    ): array {
        // 検索条件生成(近隣市区町村取得[findNearCities]する場合、近隣市区町村が他県の場合があるため都道府県[addr1_id]をセットしない)
        if (!empty($jobTypeRoma) && !empty($stateId)) {
            // 職種、市区町村
            $conditions = [
                'job_type_group' => $jobTypeRoma,
                'addr2_id_list' => collect($this->areaManager->findNearCities($stateId))->pluck('apply_city_id')->toArray()
            ];
        } elseif (!empty($jobTypeRoma)) {
            // 職種、都道府県
            $conditions = [
                'job_type_group' => $jobTypeRoma,
                'addr1_id' => $prefId
            ];
        } elseif (!empty($stateId)) {
            // 市区町村
            $conditions = [
                'addr2_id_list' => collect($this->areaManager->findNearCities($stateId))->pluck('apply_city_id')->toArray()
            ];
        } else {
            // 都道府県
            $conditions = [
                'addr1_id' => $prefId
            ];
        }
        // フリーワード検索用のデータを設定
        $conditions['freeword'] = $arrFreeword;

        // 近隣市区町村取得し、近隣データない場合は空返す
        if (isset($conditions['addr2_id_list']) && empty($conditions['addr2_id_list'])) {
            return [];
        }

        // 条件毎の求人数取得
        $addr2CountOpportunityCollect = $this->woaOpportunityManager->getAddr2CountOpportunityList($conditions, self::ADDR2_LINK_LIMIT);
        if (!empty($conditions['addr2_id_list'])) {
            $diffIdList = array_diff(
                $conditions['addr2_id_list'],
                $addr2CountOpportunityCollect->pluck('addr2')->toArray()
            );
            foreach ($diffIdList as $diffId) {
                // 0件でもリンク表示するため追加
                $addr2CountOpportunityCollect->add([
                    'addr2' => $diffId,
                    'addr2_count' => 0
                ]);
            }
        } elseif ($addr2CountOpportunityCollect->isEmpty()) {
            return [];
        }

        // 市区町村取得
        $addr2InfoCollect = $this->areaManager->getAddr2WithAddr1ByIds(
            $addr2CountOpportunityCollect->pluck('addr2')->toArray()
        );
        if ($addr2InfoCollect->isEmpty()) {
            return [];
        }

        return $this->generateNearCitiesList(
            $addr2CountOpportunityCollect->toArray(),
            $addr2InfoCollect,
            $jobTypeRoma
        );
    }

    /**
     * レスポンスデータ生成
     *
     * @param array $addr2CountOpportunityList
     * @param Collection $addr2InfoCollect
     * @param string|null $jobTypeRoma
     * @return array
     */
    private function generateNearCitiesList(
        array $addr2CountOpportunityList,
        Collection $addr2InfoCollect,
        ?string $jobTypeRoma
    ): array {
        $result = [];
        foreach ($addr2CountOpportunityList as $list) {
            $tmpAddr2 = $addr2InfoCollect->where('id', $list['addr2'])->first();
            if (empty($tmpAddr2->masterAddr1->addr1_roma)) {
                continue;
            }

            $result[] = [
                'addr1_roma' => $tmpAddr2->masterAddr1->addr1_roma,
                'addr2_roma' => $tmpAddr2->addr2_roma,
                'addr2_name' => $tmpAddr2->addr2,
                'addr2_count' => $list['addr2_count'],
                'target_url' => getJobListPageUrl(
                    null,
                    $jobTypeRoma,
                    $tmpAddr2->masterAddr1->addr1_roma,
                    $tmpAddr2->addr2_roma
                )
            ];
            if (count($result) === self::ADDR2_LINK_LIMIT) {
                break;
            }
        }

        return $result;
    }
}
