<?php

namespace App\Services;

use Google\Cloud\BigQuery\BigQueryClient;

class BigQueryService
{
    private $bigQuery;

    public function __construct()
    {
        $this->bigQuery = new BigQueryClient([
            'keyFilePath' => config('ini.GOOGLE_CLOUD_APPLICATION_CREDENTIALS'),
            'projectId'   => config('ini.GOOGLE_CLOUD_PROJECT_ID'),
        ]);
    }

    /**
     * Query生成して実行までを行う
     * 結果から特定のカラムを取得する場合は
     * array_column(iterator_to_array($queryResults), 'sf_account_id') などで取得できます
     *
     * @param string $query
     * @return \Google\Cloud\BigQuery\QueryResults
     */
    public function runQuery(string $query): \Google\Cloud\BigQuery\QueryResults
    {
        $queryJobConfig = $this->bigQuery->query($query);

        return $this->bigQuery->runQuery($queryJobConfig);
    }
}
