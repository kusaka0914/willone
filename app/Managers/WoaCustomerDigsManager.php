<?php
namespace App\Managers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * woa_customer_disg manager
 */
class WoaCustomerDigsManager
{
    /**
     * 求職者掘起し登録
     *
     * @access public
     * @param array $data
     * @return mixed integer id 登録できたら id を返す / boolean false：失敗
     */
    public function registWoaCustomerDigs($data)
    {
        $now = new Carbon();
        $params = [
            'salesforce_id'        => $data['salesforce_id'],
            'req_emp_type'         => $data['req_emp_type_text'],
            'req_date'             => $data['req_date_text'],
            'retirement_intention' => $data['retirement_intention_text'],
            'entry_route'          => $data['entry_route'],
            'salesforce_flag'      => 0,
            'mail'                 => $data['mail'],
            'action_first'         => $data['action_first'],
            'action'               => $data['action'],
            'template_id'          => $data['t'],
            'ip'                   => $data['ip'],
            'ua'                   => $data['ua'],
            'web_customer_id'      => $data['web_customer_id'],
            'regist_date'          => $now,
            'update_date'          => $now,
        ];

        $id = DB::table('woa_customer_digs')->insertGetId($params);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }
}
