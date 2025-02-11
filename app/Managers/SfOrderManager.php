<?php

namespace App\Managers;

use App\Managers\SfManager;
use DB;
use Illuminate\Support\Arr;

/**
 * SFコメディカル求職者オブジェクト関連処理
 */
class SfOrderManager
{
    const TABLE = 'kjb_opportunity__c';

    private $sfMgr;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sfMgr = new SfManager();
    }

    /**
     * SFから求職者情報を取得
     *
     * @param string $sfId SFID
     * @return object $result 求職者情報
     */
    public function getOrderById($sfId)
    {
        $siteName = strtoupper(config('ini.SITE_NAME'));

        $builder = DB::table(self::TABLE)
            ->select(
                'Id'
            )
            ->where([
                ['Id', $this->sfMgr->quote($sfId)],
                ['web_site__c', $this->sfMgr->quote($siteName)],
            ]);

        // SQL形式に変換
        $query = $this->sfMgr->convertToSql($builder);

        // SFから求職者情報を取得
        $order = $this->sfMgr->select($query);
        if ($order['totalSize'] !== 1) {
            // 取得件数が1件以外は空配列を返す
            return [];
        }

        $order = $order['records'][0];

        $result = (object) [
            'id' => $order['Id'], // Id
        ];

        return $result;
    }

    /**
     * オーダーの一覧を取得
     *
     * @return array
     */
    public function getOrderList(): array
    {
        $siteName = strtoupper(config('ini.SITE_NAME'));

        $builder = DB::table(self::TABLE)
            ->select(
                'Id',
                'Id__c',
                'web_site__c',
                'request_number__c',
                'osusumekomentp__c',
                'koyoukeitai__c',
                'KO_Gyomunaiyoubikou__c',
                'KO_Kyuyo__c',
                'KO_WorkingHours__c',
                'KO_Transportation__c',
                'kyujitukyuka__c',
                'osusumekomentqjin__c',
                'Account__r.Name',
                'Account__r.Field17__c',
                'Account__r.Field1__c',
                'Account__r.Field18__c',
                'Account__r.Field6__c',
                'Account__r.Field42__c',
                'Account__r.sisetu_keitai_input__c',
                'Account__r.Field16__c',
                'boshu_shikaku__c',
                'Account__r.BillingPostalCode',
                'Account__r.BillingState',
                'Account__r.BillingCity',
                'Account__r.BillingStreet',
                'Account__r.GIdoNumber__c',
                'Account__r.GKeidoNumber__c',
                'Name',
                'inq_number__c',
                'publicly_flag__c',
                'moyorieki11__c',
                'moyorieki22__c',
                'moyorieki33__c',
                'Account__r.timeRequired1__c',
                'Account__r.timeRequired2__c',
                'Account__r.timeRequired3__c',
                'Field1__c',
                'KO_annual_income_max__c',
                'KO_annual_income_min__c',
                'nenkankyuujitsu__c',
                'KO_RecommendedComments__c',
                'KO_AccountNameCloseSummary__c',
                'syoudannaiyousonotamemo__c',
                'DealStatus__c',
                'order_pr_title__c',
                'KO_od_status__c',
                'KO_IdealCandidate__c',
                'KO_KJA_order_presence_date__c'
            )
            ->where('web_site__c', $this->sfMgr->quote($siteName))
            ->whereIn('KO_AccountNameCloseSummary__c', [
                $this->sfMgr->quote('公開'),
                $this->sfMgr->quote('非公開'),
            ])
            ->where('Account__r.Name', '<>', 'null')
            ->where('boshu_shikaku__c', '<>', 'null')
            ->where('DealStatus__c', 'null')
            ->where('KO_WebPubliclyStateSummary__c', $this->sfMgr->quote('掲載・修正依頼'))
            ->whereRaw('(Account__r.AC_Advt_Stop_day__c = NULL OR Account__r.AC_Advt_Stop_day__c > TODAY)');

        // SQL形式に変換
        $query = $this->sfMgr->convertToSql($builder);

        // SFから求職者情報を取得
        $result = $this->sfMgr->select($query);

        if ($result['totalSize'] === 0) {
            // 該当件数0件の場合は空の配列を返す
            return [];
        }

        // 1回目リクエスト時の結果取得
        $records = $result['records'];

        // 2回目以降のリクエスト
        // SFへのリクエスト時一定件数しか取得できないので、対象データが全件取得されるまで繰り返す
        while ($result["done"] === false) {
            // SFへの高トラフィック防止
            usleep(500000); // 0.5秒

            $result = $this->sfMgr->selectNextRecords($result["nextRecordsUrl"]);
            // 2回目以降リクエスト時の結果をマージ
            $records = array_merge($records, $result['records']);
        }

        return $records;
    }

    /**
     * SFからオーダーに紐づく事業所情報を取得する（更新バッチ用）
     * @access public
     * @return array 求人一覧
     */
    public function getAccountList(): array
    {
        $siteName = strtoupper(config('ini.SITE_NAME'));

        $builder = DB::table('kjb_opportunity__c')
            ->select(
                'Id',
                'Account__c',
                'Account__r.Field1__c',
                'Account__r.BillingState',
                'Account__r.BillingCity',
                'Account__r.BillingStreet',
            )
            ->where([
                ['web_site__c', $this->sfMgr->quote($siteName)],
                ['Account__r.Field1__c', '<>', 'null'],
            ]);

        // SQL形式に変換
        $query = $this->sfMgr->convertToSql($builder);

        // SFから求職者情報を取得
        $result = $this->sfMgr->select($query);

        if ($result['totalSize'] == 0) {
            // 取得件数が0件の場合は空配列を返す
            return [];
        }

        // 1回目リクエスト時の結果取得
        $records = $this->convertArray($result['records']);

        // 2回目以降のリクエスト
        // SFへのリクエスト時一定件数しか取得できないので、対象データが全件取得されるまで繰り返す
        while ($result["done"] === false) {
            // SFへの高トラフィック防止
            usleep(500000); // 0.5秒

            $result = $this->sfMgr->selectNextRecords($result["nextRecordsUrl"]);
            // 2回目以降リクエスト時の結果をマージ
            $records = array_merge($records, $this->convertArray($result['records']));
        }

        return $records;
    }

    /**
     * 必要な配列のみに加工する
     * @access private
     * @param array $list
     * @return array
     */
    private function convertArray(array $list): array
    {
        $result = [];
        foreach ($list as $key => $item) {
            $result[$key] = [
                'Id'            => Arr::get($item, 'Id'),
                'Account__c'    => Arr::get($item, 'Account__c'),
                'Field1__c'     => Arr::get($item, 'Account__r.Field1__c'),
                'BillingState'  => Arr::get($item, 'Account__r.BillingState'),
                'BillingCity'   => Arr::get($item, 'Account__r.BillingCity'),
                'BillingStreet' => Arr::get($item, 'Account__r.BillingStreet'),
            ];
        }

        return $result;
    }
}
