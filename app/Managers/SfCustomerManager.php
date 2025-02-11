<?php

namespace App\Managers;

use App\Managers\SfManager;
use DB;

/**
 * SFコメディカル求職者オブジェクト関連処理
 */
class SfCustomerManager
{
    const TABLE = 'kjb_customer__c';
    // メール配信除外キューSFID
    const SFID_MAIL_DELIVERY_EXCLUSION_QUEUE = '00G10000001BxhU';

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
    public function getCustomerById($sfId)
    {
        $siteName = strtoupper(config('ini.SITE_NAME'));

        $builder = DB::table(self::TABLE)
            ->select(
                'Id',
                'web_customer_id__c',
                'web_site__c',
                'Name',
                'mail__c',
                'tel__c',
                'license__c',
                'OwnerId',
                'GIdoNumber__c',
                'GKeidoNumber__c',
                'non_web_update_flag__c',
                'addr1Edit__c',
                'addr2Edit__c',
                'age_cal__c',
                'KC_graduation_year__c',
                'status__c',
                'KC_Delivery_CPName__c',
                'KC_Delivery_CPCorrection__c',
                'delete_personal_info_flag__c',
            )
            ->where([
                ['Id', $this->sfMgr->quote($sfId)],
                ['web_site__c', $this->sfMgr->quote($siteName)],
            ]);

        // SQL形式に変換
        $query = $this->sfMgr->convertToSql($builder);

        // SFから求職者情報を取得
        $customer = $this->sfMgr->select($query);
        if ($customer['totalSize'] !== 1) {
            // 取得件数が1件以外は空配列を返す
            return [];
        }

        $customer = $customer['records'][0];

        $result = (object) [
            'id'                        => $customer['Id'], // Id
            'customer_id'               => $customer['web_customer_id__c'], // Webサイト求職者ID
            'site_name'                 => $customer['web_site__c'], // WEBサイト
            'name_kan'                  => $this->sfMgr->kanaTrim($customer['Name']), // 氏名
            'mail'                      => $customer['mail__c'], // メールアドレス①
            'tel'                       => $customer['tel__c'], // 電話番号
            'license'                   => $customer['license__c'], // 保有資格(編集用)
            'owner'                     => $customer['OwnerId'], // 所有者
            'longitude'                 => $customer['GIdoNumber__c'], // 緯度
            'latitude'                  => $customer['GKeidoNumber__c'], // 経度
            'non_web_update_flag'       => $customer['non_web_update_flag__c'], // 基本情報web連携除外フラグ
            'addr1'                     => $customer['addr1Edit__c'], // 都道府県（編集用）
            'addr2'                     => $customer['addr2Edit__c'], // 市区町村（編集用）
            'age'                       => $customer['age_cal__c'], // 年齢,
            'graduation_year'           => $customer['KC_graduation_year__c'], // 卒業予定年,
            'status'                    => $customer['status__c'], // 求職者ステータス,
            'cp_name'                   => $customer['KC_Delivery_CPName__c'], // 割振りCP名
            'cp_correction'             => $customer['KC_Delivery_CPCorrection__c'], // 割振りCP名（修正）
            'delete_personal_info_flag' => $customer['delete_personal_info_flag__c'], // 個人情報削除フラグ
        ];

        return $result;
    }

    /**
     * SFからメールアドレス又は電話番号で求職者情報を取得（自WEB求職者IDは除く）
     *
     * @param string $mail メールアドレス
     * @param string $tel 電話番号
     * @param string $webCustomerId WEB求職者ID
     * @return Collection $result 求職者情報リスト
     */
    public function getCustomerByMailOrTel($mail, $tel, $webCustomerId)
    {
        $siteName = strtoupper(config('ini.SITE_NAME'));

        $builder = DB::table(self::TABLE)
            ->select(
                'Id',
                'license__c',
                'OwnerId',
                'non_web_update_flag__c',
                'age_cal__c',
                'KC_graduation_year__c',
            )
            ->where([
                ['web_site__c', $this->sfMgr->quote($siteName)],
                ['OwnerId', '!=', $this->sfMgr->quote(self::SFID_MAIL_DELIVERY_EXCLUSION_QUEUE)],
                ['web_customer_id__c', '!=', $this->sfMgr->quote($webCustomerId)],
            ]);

        if (!empty($mail) && !empty($tel)) {
            $mail = $this->sfMgr->quote($mail);
            $tel = $this->sfMgr->quote($tel);

            $builder->where(function ($builder) use ($mail, $tel) {
                $builder->where(function ($builder) use ($mail) {
                    $builder->where('mail__c', $mail)
                        ->orWhere('mail_sub__c', $mail)
                        ->orWhere('mailEdit__c', $mail)
                        ->orWhere('mail_subEdit__c', $mail);
                })
                    ->orWhere(function ($builder) use ($tel) {
                        $builder->where('tel__c', $tel)
                            ->orWhere('denwabangouabu__c', $tel)
                            ->orWhere('telEdit__c', $tel)
                            ->orWhere('denwabangouabuEdit__c', $tel);
                    });
            });
        } elseif (!empty($mail)) {
            $mail = $this->sfMgr->quote($mail);

            $builder->where(function ($builder) use ($mail) {
                $builder->where('mail__c', $mail)
                    ->orWhere('mail_sub__c', $mail)
                    ->orWhere('mailEdit__c', $mail)
                    ->orWhere('mail_subEdit__c', $mail);
            });
        } elseif (!empty($tel)) {
            $tel = $this->sfMgr->quote($tel);

            $builder->where(function ($builder) use ($tel) {
                $builder->where('tel__c', $tel)
                    ->orWhere('denwabangouabu__c', $tel)
                    ->orWhere('telEdit__c', $tel)
                    ->orWhere('denwabangouabuEdit__c', $tel);
            });
        }

        // SQL形式に変換
        $query = $this->sfMgr->convertToSql($builder);

        // SFから求職者情報を取得
        $customers = $this->sfMgr->select($query);
        if ($customers['totalSize'] === 0) {
            return [];
        }

        $customers = $customers['records'];
        $result = collect();
        foreach ($customers as $customer) {
            $result[] = (object) [
                'id'                  => $customer['Id'], // Id
                'license'             => $customer['license__c'], // 保有資格(編集用)
                'owner'               => $customer['OwnerId'], // 所有者
                'non_web_update_flag' => $customer['non_web_update_flag__c'], // 基本情報web連携除外フラグ
                'age'                 => $customer['age_cal__c'], // 年齢,
                'graduation_year'     => $customer['KC_graduation_year__c'], // 卒業予定年,
            ];
        }

        return $result;
    }

    /**
     * SFから求職者情報を取得
     * ※SFにリアルタイムでSELECT
     *
     * @param  string $user 求職者のSFID
     * @return object SFの求職者情報
     */
    public function getSfCustomer($user)
    {
        $result = "";
        if (!empty($user)) {
            // SFから求職者情報の取得
            $res = $this->getCustomerById($user);

            if (!empty($res->site_name) && $res->site_name == 'WOA') {
                // customer_short_urlのカラムに合わせる
                $result = (object) [
                    'id'              => '',
                    'salesforce_id'   => $res->id,
                    'short_url_id'    => '',
                    'site_id'         => null,
                    'web_customer_id' => $res->customer_id,
                    'name'            => $res->name_kan,
                    'mail'            => $res->mail,
                    'tel'             => $res->tel,
                    'license'         => $res->license,
                    'addr1'           => $res->addr1,
                    'addr2'           => $res->addr2,
                    'longitude'       => $res->longitude,
                    'latitude'        => $res->latitude,
                    'age'             => $res->age,
                    'graduation_year' => $res->graduation_year,
                    'status'          => $res->status,
                    'cp_name'         => $res->cp_name,
                    'cp_correction'   => $res->cp_correction,
                ];
            }
        }

        return $result;
    }

    /**
     * 登録履歴から求職者情報を取得
     * @param string $web_customer_id web求職者ID
     * @return object $result 求職者情報
     */
    public function getSfCustomerFromRegistrationHistory($webCustomerId)
    {
        $siteName = strtoupper(config('ini.SITE_NAME'));
        $builder = DB::table('kjb_RegistrationHistory__c')
            ->select(
                'Name',
                'KR_kjb_customer__c',
                'KR_mail__c',
                'KR_OwnerId__c'
            )
            ->where([
                ['KR_web_customer_id__c', $this->sfMgr->quote($webCustomerId)],
                ['KR_web_site__c', $this->sfMgr->quote($siteName)],
            ])
            ->orderBy('KR_EntryDateTime__c', 'desc');

        // SQL形式に変換
        $query = $this->sfMgr->convertToSql($builder);

        $customerInfo = $this->sfMgr->select($query);
        if ($customerInfo['totalSize'] == 0) {
            // 取得件数が0件の場合は空配列を返す
            return [];
        }
        // 最新のレコード一件を取得
        $customerInfo = $customerInfo['records'][0];

        $result = (object) [
            'name'          => $this->sfMgr->kanaTrim($customerInfo['Name']),
            'salesforce_id' => $customerInfo['KR_kjb_customer__c'],
            'mail'          => $customerInfo['KR_mail__c'],
            'owner'         => $customerInfo['KR_OwnerId__c'],
        ];

        return $result;
    }
}
