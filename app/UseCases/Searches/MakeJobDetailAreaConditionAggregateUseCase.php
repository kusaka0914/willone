<?php

declare (strict_types = 1);

namespace App\UseCases\Searches;

use App\Services\MakeAggregateDataService;

class MakeJobDetailAreaConditionAggregateUseCase
{

    /** @var makeAggregateDataService */
    private $makeAggregateDataService;

    public function __construct(
        MakeAggregateDataService $makeAggregateDataService
    ) {
        $this->makeAggregateDataService = $makeAggregateDataService;
    }

    /**
     * @param array $params
     * @return array
     */
    final public function __invoke(array $params): array
    {
        return $this->makeAggregateDataService->makeAggregateData($params);
    }
}
