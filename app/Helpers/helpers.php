<?php

if (!function_exists('mb_truncate')) {
    /**
     * 指定した文字数で文章を切り、任意の文字列を追加する。
     *
     * @param  string  $string
     * @param  int     $length
     * @param  string  $etc
     * @return string
     */
    function mb_truncate($string, $length = 80, $etc = '...')
    {
        if ($length == 0) {
            return '';
        }
        if (mb_strlen($string, "UTF-8") > $length) {
            $string = mb_substr($string, 0, $length, "UTF-8");

            return $string . $etc;
        } else {
            return $string;
        }
    }
}

if (!function_exists('getCanonicalUrl')) {
    /**
     * @param string|null $currentRouteName
     * @param string $currentUrl
     * @return string
     */
    function getCanonicalUrl(?string $currentRouteName, string $currentUrl): string
    {
        if (empty($currentRouteName) || !in_array($currentRouteName, config('canonical.SHAVE_FIRST_PAGE_ROUTE_NAME_LIST'), true)) {
            return $currentUrl;
        }

        return preg_replace('/\/1$/', '', $currentUrl);
    }
}

if (!function_exists('errorLogCommonOutput')) {
    /**
     * エラーログ共通出力
     *
     * @param Exception $e
     * @return string
     */
    function errorLogCommonOutput(\Exception $e): string
    {
        return $e->getFile() . '(' . $e->getLine() . '): ' . $e->getMessage() . "\n" . $e->getTraceAsString();
    }
}

if (!function_exists('addQuery')) {
    /**
     * public配下からのファイルパスにキャッシュバスティングのパラメータを付与
     * ex)
     *  /woa/css/style.css => /woa/css/style.css?20221212112808
     *  /woa/img/1.png => /woa/img/1.png?20221206135347
     *  /css/style.css => /css/style.css?20221212112808
     *
     * @param string $filePath
     * @return string
     * @throws Exception
     */
    function addQuery(string $filePath): string
    {
        try {
            $checkFilePath = preg_replace('/^\/woa\//', '/', $filePath);

            return $filePath . '?' . date('YmdHis', filemtime(public_path() . $checkFilePath));
        } catch (\Exception $e) {
            if (config('app.env') !== 'production') {
                throw new \Exception('ファイルがありませんでした。' . $filePath);
            }

            // NOTE: 本番環境のファイルを間違えて削除した際にaddQuery利用していてファイル存在しない場合に500エラーになってしまうので下記対応をする
            report($e);

            return $filePath;
        }
    }
}

if (!function_exists('getStationText')) {
    /**
     * 「駅名 + 徒歩x分」 のテキスト生成
     *
     * @param string|null $station
     * @param int|null $minutesWalk
     * @return string
     */
    function getStationText(?string $station, ?int $minutesWalk): string
    {
        if (empty($station)) {
            return '';
        }

        return $station . (!is_null($minutesWalk) ? " 徒歩{$minutesWalk}分" : '');
    }
}

if (!function_exists('getJobTypeGroupNameFromJobType')) {
    /**
     * woa_opportunity.job_type から 職種グループ名 を返す
     *
     * @param string|null $jobType
     * @param string $joinText
     * @return string
     */
    function getJobTypeGroupNameFromJobType(?string $jobType, string $joinText = ','): string
    {
        $jobTypeIds = array_filter(array_map('trim', explode(',', $jobType)), 'strlen');
        if (empty($jobTypeIds)) {
            return '';
        }
        $jobTypeGroupList = config('ini.JOB_TYPE_GROUP');
        $result = [];
        foreach ($jobTypeIds as $jobTypeId) {
            foreach ($jobTypeGroupList as $jobTypeGroup) {
                if (in_array((int) $jobTypeId, $jobTypeGroup['ids'], true)) {
                    $result[] = $jobTypeGroup['name'];
                    break;
                }
            }
        }

        return implode($joinText, array_unique($result));
    }
}

if (!function_exists('getPaginationFromToNumText')) {
    /**
     * ページネーションの件数部分のテキスト生成
     * ex.「件中 1 ～ 30件を表示」
     *
     * @param string|null $pageNo
     * @param int $currentCount
     * @param int $totalCount
     * @param int $perPageLimit
     * @return string
     */
    function getPaginationFromToNumText(
        ?string $pageNo,
        int $currentCount,
        int $totalCount,
        int $perPageLimit
    ): string {
        // 型変換
        $pageNo = empty($pageNo) ? null : (int) $pageNo;

        // ページの表示件数0件 or 最大ページ数over
        $limitPageNo = ceil($totalCount / $perPageLimit);
        if (empty($currentCount) || (!empty($pageNo) && $pageNo > $limitPageNo)) {
            // 表示テキスト 現行踏襲
            return ' 件中 0 〜 0 件を表示';
        }

        // fromNum, toNum生成
        if (empty($pageNo) || $pageNo === 1) {
            $fromNum = 1;
            $toNum = $currentCount;
        } else {
            // 2ページ目以降
            $fromNum = (($pageNo - 1) * $perPageLimit) + 1;
            $toNum = (($pageNo - 1) * $perPageLimit) + $currentCount;
        }

        return " 件中 {$fromNum} 〜 {$toNum} 件を表示";
    }
}

if (!function_exists('setNoindexJob')) {
    /**
     * 検索画面でnoindexか判定
     *
     * @param array $noindexData
     * @return integer
     */
    function setNoindexJob(array $noindexData): int
    {
        $noindex = 0;
        if (!empty($noindexData['id']) && !in_array($noindexData['id'], config('ini.INDEX_JOB_TYPE'))) {
            // 職種を先に見る
            $noindex = 1;
        } elseif ($noindexData['noindex'] === true) {
            $noindex = 1;
        } elseif ($noindexData['job_data']->isEmpty()) {
            $noindex = 1;
        }

        return $noindex;
    }
}

if (!function_exists('getJobListPageUrl')) {
    /**
     * 求人一覧ページのURL生成
     *
     * @param string|null $routeName
     * @param string|null $jobTypeRoma
     * @param string $prefRoma
     * @param string|null $stateRoma
     * @return string|null
     */
    function getJobListPageUrl(
        ?string $routeName,
        ?string $jobTypeRoma,
        string $prefRoma,
        ?string $stateRoma
    ): ?string {
        // ルート名($routeName)がnullの場合はrouteNameをパラメータから推察
        if (empty($routeName)) {
            if (!empty($jobTypeRoma) && !empty($prefRoma) && !empty($stateRoma)) {
                $routeName = 'JobAreaStateSelect';
            } elseif (!empty($jobTypeRoma) && !empty($prefRoma)) {
                $routeName = 'JobAreaSelect';
            } elseif (!empty($prefRoma) && !empty($stateRoma)) {
                $routeName = 'AreaStateSelect';
            } else {
                $routeName = 'AreaSelect';
            }
        }

        switch ($routeName) {
            case 'JobAreaStateSelect':
                $param = [
                    'id'    => $jobTypeRoma,
                    'pref'  => $prefRoma,
                    'state' => $stateRoma,
                ];
                break;
            case 'JobAreaSelect':
                $param = [
                    'id'   => $jobTypeRoma,
                    'pref' => $prefRoma,
                ];
                break;
            case 'AreaStateSelect':
                $param = [
                    'pref'  => $prefRoma,
                    'state' => $stateRoma,
                ];
                break;
            case 'AreaSelect':
                $param = [
                    'id' => $prefRoma,
                ];
                break;
            default:
                return null;
        }

        return route($routeName, $param);
    }
}

if (!function_exists('getRelNofollowAttrText')) {
    /**
     * rel="nofollow" テキスト生成
     *
     * @param mixed $data
     * @param string $leadingSpace
     * @param string $trailingSpace
     * @return string
     */
    function getRelNofollowAttrText(
        $data,
        string $leadingSpace = ' ',
        string $trailingSpace = ''
    ): string {
        if (!empty($data)) {
            return '';
        }

        return $leadingSpace . 'rel="nofollow"' . $trailingSpace;
    }
}

if (!function_exists('createJobListPageUrl')) {
    /**
     * rel="nofollow" テキスト生成
     *
     * @param mixed $data
     * @param string $leadingSpace
     * @param string $trailingSpace
     * @return string
     */
    function createJobListPageUrl(
        string $typeRoma,
        string $prefRoma,
        ?string $stateRoma,
        string $paramName,
        string $paramVAlue
    ): string {
        if (!empty($stateRoma)) {
            if ($paramName == 'employ') {
                $routeName = 'JobAreaStateSelectEmploy';
            } elseif ($paramName == 'business') {
                $routeName = 'JobAreaStateSelectBusiness';
            }
        } else {
            if ($paramName == 'employ') {
                $routeName = 'JobAreaSelectEmploy';
            } elseif ($paramName == 'business') {
                $routeName = 'JobAreaSelectBusiness';
            }
        }
        if (!empty($stateRoma)) {
            $link = route($routeName, ['id' => $typeRoma, 'pref' => $prefRoma, 'state' => $stateRoma, $paramName => $paramVAlue]);
        } else {
            $link = route($routeName, ['id' => $typeRoma, 'pref' => $prefRoma, $paramName => $paramVAlue]);
        }

        return $link;
    }
}

if (!function_exists('getJobIdFromTypeRoma')) {
    /**
     * $typeRoma ex:judoseifukushi からiniファイルのmaster_license_mst.idを返す
     * (masterのキャッシュ化とデータの正規化ができたらmasterから取得したい)
     * 値が取得できない場合のdefaultは柔道整復師の40
     *
     * @param string|null $typeRoma
     * @return int
     */
    function getJobIdFromTypeRoma(?string $typeRoma): int
    {

        return config('ini.JOB_TYPE_GROUP')[$typeRoma]['master_id'] ?? 40;
    }
}

if (!function_exists('getS3ImageUrl')) {
    /**
     * s3のWeb image urlを返却
     *
     * @param string|null $filePath
     * @param string $topDir
     * @return string
     */
    function getS3ImageUrl(?string $filePath, string $topDir = '/woa'): string
    {
        if (empty($filePath)) {
            return '';
        }

        return config('ini.S3_CO_IMAGE_URL') . $topDir . $filePath;
    }
}

if (!function_exists('convertToGregorian')) {
    /**
     * 令和年を西暦に変換
     * 令和元年は2019年から始まったので2018年に令和年を足して西暦を計算
     * 令和n年 => 2018 + n
     *
     * @param string $reiwaYear
     * @return string
     */
    function convertToGregorian(string $reiwaYear): string
    {
        // 「令和」と「年」の間の数字を取得する
        preg_match('/令和(\d+)年/', $reiwaYear, $matches);
        $reiwaYear = $matches[1];
        if ($reiwaYear < 1) {
            return '無効な令和年';
        }

        return 2018 + $reiwaYear;
    }
}

if (!function_exists('sfIdCheckAndExtract')) {
    /**
     * sfIdが正常なら15桁にしたsfIdを返す
     *
     * @param string|null
     * @return string
     */
    function sfIdCheckAndExtract(?string $sfId): string
    {
        $userLength = 23;
        $userCheckLength = 8;
        if (strlen($sfId) == $userLength && preg_match('/^[a-zA-Z0-9]+$/', $sfId)) {
            return substr($sfId, 0, strlen($sfId) - $userCheckLength);
        }

        return "";
    }
}
