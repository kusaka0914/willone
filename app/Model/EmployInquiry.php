<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmployInquiry extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';
    const SF_LINK_LIMIT = 30;

    protected $table = 'employ_inquiry';

    /**
     * メール引合SF連携対象データを取得
     *
     * @param string $id ID
     * @return Collection $result SF連携対象求職者
     */
    public function getSfLinkDataForAccountMail($id = null)
    {
        if (!empty($id)) {
            // 即時連携用
            $result = $this
                ->where([
                    ['id', $id],
                    ['sent_address', '!=', ''],
                    ['order_tel_contact', '!=', ''],
                    ['shubetu', '!=', ''],
                    ['delete_flag', 0],
                    ['salesforce_flag', 0],
                ])
                ->get();
        } else {
            // 定期連携用
            $result = $this
                ->where([
                    ['sent_address', '!=', ''],
                    ['order_tel_contact', '!=', ''],
                    ['shubetu', '!=', ''],
                    ['delete_flag', 0],
                ])
                ->whereIn('salesforce_flag', [0, 2])
                ->whereRaw('regist_date < (NOW() - INTERVAL 5 MINUTE)')
                ->orderBy('update_date')
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
}
