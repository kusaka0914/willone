<?php
namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class WoaCustomerDigs extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';
    const SF_LINK_LIMIT = 50;

    protected $table = 'woa_customer_digs';

    /**
     * SF連携対象求職者を取得
     *
     * @param string $id ID
     * @return Collection $result SF連携対象求職者
     */
    public function getSfLinkData($id = null)
    {
        $builder = DB::table("{$this->table} AS wcd")
            ->leftJoin('woa_customer AS wc', 'wcd.web_customer_id', '=', 'wc.id')
            ->select(
                'wcd.id',
                'wcd.salesforce_id',
                'wcd.req_emp_type',
                'wcd.req_date',
                'wcd.retirement_intention',
                'wcd.entry_route',
                'wcd.action',
                'wcd.action_first',
                'wcd.template_id',
                'wcd.ip',
                'wcd.ua',
                'wcd.update_date',
                'wc.mail',
                'wc.tel',
                'wc.birth',
                'wcd.web_customer_id',
                'wc.regist_date',
            );

        if (!empty($id)) {
            // 即時連携用
            $result = $builder
                ->where([
                    ['wcd.id', $id],
                    ['wcd.salesforce_flag', 0],
                    ['wcd.delete_flag', 0],
                ])
                ->get();
        } else {
            // 定期連携用
            $result = $builder
                ->whereIn('wcd.salesforce_flag', [0, 2])
                ->whereRaw('wcd.regist_date < (NOW() - INTERVAL 5 MINUTE)')
                ->where('wcd.delete_flag', 0)
                ->orderBy('wcd.update_date')
                ->limit(self::SF_LINK_LIMIT)
                ->get();
        }

        return $result;
    }

    /**
     * SF連携フラグ更新
     *
     * @param string $id ID
     * @param string $sfFlag SF連携フラグ
     * @param string $sfIdReal 実際のSFID
     * @return string $result 更新結果
     */
    public function updateSfFlag($id, $sfFlag, $sfIdReal = null)
    {
        $update = ['salesforce_flag' => $sfFlag];
        if (!empty($sfIdReal)) {
            $update['salesforce_id_real'] = $sfIdReal;
        }

        $result = $this
            ->where('id', $id)
            ->update($update);

        return $result;
    }
}
