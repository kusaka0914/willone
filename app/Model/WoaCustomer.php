<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WoaCustomer extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';
    const SF_LINK_LIMIT = 100;

    protected $table = 'woa_customer';

    /**
     * SF連携対象求職者を取得
     *
     * @param string $id ID
     * @return Collection $result SF連携対象求職者
     */
    public function getSfLinkData($id = null)
    {
        if (!empty($id)) {
            // 即時連携用
            $result = $this
                ->select("{$this->table}.*", 'fr.cp_sms_id', 'fr.referral_salesforce_id', 'fr.referral_name')
                ->leftJoin('friend_referral AS fr', "{$this->table}.id", '=', 'fr.customer_id')
                ->where([
                    ["{$this->table}.id", $id],
                    ['salesforce_flag', 0],
                    ["{$this->table}.delete_flag", 0],
                ])
                ->get();
        } else {
            // 定期連携用
            $result = $this
                ->select("{$this->table}.*", 'fr.cp_sms_id', 'fr.referral_salesforce_id', 'fr.referral_name')
                ->leftJoin('friend_referral AS fr', "{$this->table}.id", '=', 'fr.customer_id')
                ->whereIn('salesforce_flag', [0, 2])
                ->whereRaw("{$this->table}.regist_date < (NOW() - INTERVAL 5 MINUTE)")
                ->where("{$this->table}.delete_flag", 0)
                ->orderBy("{$this->table}.update_date")
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
     * @return string $result 更新結果
     */
    public function updateSfFlag($id, $sfFlag)
    {
        $result = $this
            ->where('id', $id)
            ->update([
                'salesforce_flag' => $sfFlag,
            ]);

        return $result;
    }

    /**
     * 黒本経由等SF連携対象求職者を取得
     *
     * @param integer $customerId 求職者ID
     * @return object $results 求職者データ
     */
    public function getCustomer($customerId)
    {
        $results = $this
                ->where([
                    ['id', $customerId],
                    ['delete_flag', 0],
                ])
                ->first();

        return $results;
    }
}
