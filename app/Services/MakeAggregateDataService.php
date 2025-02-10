<?php

namespace App\Services;

use App\Model\WoaAreaConditionAggregate;

class MakeAggregateDataService
{

    /**
     * constructor
     * @param WoaAreaConditionAggregate $woaAreaConditionAggregate
     */
    public function __construct(WoaAreaConditionAggregate $woaAreaConditionAggregate)
    {
        $this->woaAreaConditionAggregate = $woaAreaConditionAggregate;
    }

    /**
     * 絞り込みデータ整形
     * @param array $params
     * @param array $aggregateDataArray
     */
    public function makeAggregateData(array $params): array
    {
        $aggregateData = $this->woaAreaConditionAggregate->getAggregateData($params);
        $aggregateDataArray = [
            'all',
            'fulltime'         => 0,
            'parttime'         => 0,
            'ekichika5'        => 0,
            'employDisplay'    => false,
            'bussinessDisplay' => false,
            'ekichika5Display' => false,
            'stateDisplay'     => false,
        ];
        if ($aggregateData->isEmpty()) {
            return $aggregateDataArray;
        }
        foreach ($aggregateData as $one) {
            if ($one->addr2 == '') {
                continue;
            }
            // 市区町村格納
            if ($one->search_key == 'state') {
                $aggregateDataArray['state'][] = $one;
                $aggregateDataArray['stateDisplay'] = true;
            }
            if ($one->conditions == 'employ') {
                foreach (config('ini.EMPLOY_TYPE') as $employType) {
                    if ($one->search_key == $employType['search_key']) {
                        $aggregateDataArray[$employType['search_key']] = $aggregateDataArray[$employType['search_key']] + $one->sum;
                        $aggregateDataArray['employDisplay'] = true;
                    }
                }
            } elseif ($one->conditions == 'business') {
                foreach (config('ini.BUSINESS_TYPE') as $businessType) {
                    if ($one->search_key == $businessType['search_key']) {
                        if (empty($aggregateDataArray[$businessType['search_key']])) {
                            $aggregateDataArray[$businessType['search_key']] = 0;
                        }
                        $aggregateDataArray[$businessType['search_key']] = $aggregateDataArray[$businessType['search_key']] + $one->sum;
                        $aggregateDataArray['bussinessDisplay'] = true;
                    }
                }
            } elseif ($one->conditions == 'ekichika5') {
                $aggregateDataArray['ekichika5'] = $aggregateDataArray['ekichika5'] + $one->sum;
                $aggregateDataArray['ekichika5Display'] = true;
            }
        }

        return $aggregateDataArray;
    }
}
