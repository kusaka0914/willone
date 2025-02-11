<?php
/**
 *  WoaCustomerManager
 */

namespace App\Managers;

use Illuminate\Support\Facades\DB;

class WoaCustomerManager
{
    const AGREEMENT_FLAG_AGREE = 1; // 規約に同意フラグ 同意
    const SALESFORCE_FLAG_NOT_LINKED = 0; // SF連携フラグ 未連携
    const SALESFORCE_FLAG_EXCLUDED = 9; // SF連携フラグ 対象外

    /**
     * 求職者登録
     *
     * @access public
     * @param array $data
     * @param boolean $api_mode true:求職者登録API実行時 / false:それ以外
     * @return mixed integer id 登録できたら id を返す / boolean false：失敗
     */
    public function registWoaCustomer($data, $api_mode = false)
    {
        $now = date('Y-m-d H:i:s');
        $params = [];
        if ($api_mode) {
            // APIモードONの時
            $params = [
                'name_kan'                => $data['name_kan'],
                'name_cana'               => $data['name_cana'],
                'birth'                   => $data['birth'],
                'addr1'                   => $data['addr1'],
                'addr2'                   => $data['addr2'],
                'addr3'                   => $data['addr3'],
                'tel'                     => $data['tel'],
                'mail'                    => $data['mail'] ?? '',
                'license'                 => $data['license'],
                'req_emp_type'            => $data['req_emp_type'],
                'req_date'                => $data['req_date'],
                'retirement_intention'    => $data['retirement_intention'],
                'graduation_year'         => $data['graduation_year'],
                'src_site_name'           => $data['src_site_name'],
                'src_customer_id'         => $data['src_customer_id'] ?? null,
                'action'                  => $data['action'],
                'action_first'            => $data['action_first'],
                'template_id'             => $data['template_id'],
                'service_usage_intention' => $data['branch'] ?? null,
                'ip'                      => $data['ip'],
                'ua'                      => $data['ua'],
                'entry_memo'              => $data['entry_memo'] ?? '',
                'agreement_flag'          => self::AGREEMENT_FLAG_AGREE,
                'salesforce_flag'         => $data['salesforce_flag'],
                'regist_date'             => $now,
                'update_date'             => $now,
            ];
        } else {
            // 通常登録時
            $params = [
                'name_kan'                => $data['name_kan'],
                'name_cana'               => $data['name_cana'],
                'birth'                   => $data['birth'],
                'zip'                     => $data['zip'],
                'addr1'                   => $data['addr1'],
                'addr2'                   => $data['addr2'],
                'addr3'                   => $data['addr3'],
                'tel'                     => $data['mob_phone'],
                'mail'                    => $data['mail'],
                'license'                 => $data['license_text'],
                'req_emp_type'            => $data['req_emp_type_text'],
                'req_date'                => $data['req_date_text'],
                'retirement_intention'    => $data['retirement_intention_text'],
                'graduation_year'         => $data['graduation_year'],
                'entry_order'             => $data['entry_order'],
                'introduce_name'          => $data['introduce_name'] ?? null,
                'action'                  => $data['action'],
                'action_first'            => $data['action_first'],
                'entry_category_manual'   => $data['entry_category_manual'],
                'template_id'             => $data['t'],
                'service_usage_intention' => $data['branch'] ?? null,
                'ip'                      => $data['ip'],
                'ua'                      => $data['ua'],
                'entry_memo'              => $data['entry_memo'],
                'agreement_flag'          => $data['agreement_flag'],
                'salesforce_flag'         => 0,
                'moving_flg'              => $data['moving_flg'] ?? null,
                'regist_date'             => $now,
                'update_date'             => $now,
            ];
        }

        $id = DB::table('woa_customer')->insertGetId($params);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }
}
