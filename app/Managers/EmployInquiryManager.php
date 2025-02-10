<?php
namespace App\Managers;

use Illuminate\Support\Facades\DB;

class EmployInquiryManager
{
    // 採用担当問い合わせ入力情報項目
    const ENTRY_PARAMETER_MAPPING = [
        'company_name' => 'company_name',
        'name_kan' => 'name_kan',
        'name_cana' => 'name_cana',
        'division_name' => 'division_name',
        'addr1' => 'addr1',
        'addr2' => 'addr2',
        'addr3' => 'addr3',
        'tel' => 'tel', 
        'mail' => 'mail',
        'inquiry' => 'inquiry',
        'inquiry_detail' => 'inquiry_detail',
        'tel_time_id' => 'tel_time_id',
        'tel_time_note' => 'tel_time_note',
        'account' => 'account',
        'sent_address' => 'sent_address',
        'order_tel_contact' => 'order_tel_contact',
        'shubetu' => 'shubetu',
    ];

    // 配信停止入力情報項目
    const OPTOUT_PARAMETER_MAPPING = [
        'mail' => 'mail',
        'stop_reason' => 'stop_reason',
        'sent_address' => 'sent_address',
        'order_tel_contact' => 'order_tel_contact',
        'shubetu' => 'shubetu', 
    ];

    /**
     * 採用担当問い合わせ入力情報を登録する
     *
     * @param data
     * @param siteId
     * @param action
     * @return unknown_type 登録できたら id を返す
     */
    public function createEntryEmployInquiry($data, $siteId, $actionParams)
    {
        // Requestパラメータから追加する採用担当問い合わせ入力情報項目とデータを設定する
        $params = collect($data)->only(array_keys(self::ENTRY_PARAMETER_MAPPING));

        // レコード追加用パラメータを作成する
        $values = $this->makeValues($params, $siteId, $actionParams);

        $id = DB::table('employ_inquiry')->insertGetId($values);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

    /**
     * 配信停止入力情報を登録する
     *
     * @param data
     * @param siteId
     * @param action
     * @return unknown_type 登録できたら id を返す
     */
    public function createOptoutEmployInquiry($data, $siteId, $actionParams)
    {
        // Requestパラメータから追加する採用担当問い合わせ入力情報項目とデータを設定する
        $params = collect($data)->only(array_keys(self::OPTOUT_PARAMETER_MAPPING));

        // [FIXME] マジックナンバーっぽいものの扱いの解決
        // MySQLのstrict modeに引っかかる項目のみを設定する
        $decoration_params = ['addr1' => 11, 'addr2' => 11001, 'addr3' => '', 'tel' => ''];
        $params = $params->merge($decoration_params);

        // レコード追加用パラメータを作成する
        $values = $this->makeValues($params, $siteId, $actionParams);

        $id = DB::table('employ_inquiry')->insertGetId($values);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

    /**
     * レコード追加用のパラメータを作成する
     *
     * @param data
     * @param siteId
     * @param action
     * @return array
     */
    private function makeValues($params, $siteId, $actionParams)
    {
        $date = date('Y-m-d H:i:s');

        // サイトIDを設定する
        $values = $params->merge(['site_id' => $siteId]);
        // アクションパラメータを設定する
        if ($actionParams) {
            $values = $values->merge($actionParams);
        }
        // その他パラメータを設定する
        $values = $values->merge(['salesforce_flag' => 0,
            'regist_date' => $date,
            'update_date' => $date,
            'delete_flag' => 0]);

        return $values->all();
    }
}
