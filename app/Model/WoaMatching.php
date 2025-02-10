<?php
namespace App\Model;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WoaMatching extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';
    const SF_LINK_LIMIT = 50;

    protected $table = 'woa_matching';

    /**
     * マッチング登録
     *
     * @access public
     * @param array $data
     * @return mixed integer id 登録できたら id を返す / boolean false：失敗
     */
    public function registWoaMatching($data)
    {
        $now = new Carbon();
        $params = [
            'salesforce_id'        => $data['salesforce_id'],
            'order_salesforce_id'  => $data['orderId'],
            'entry_status'         => $data['entryStatus'], // 受注までのステータス（0:求人エントリー，1:求人詳細問合せ）
            'action'               => $data['action'],
            'action_first'         => $data['action_first'],
            'via_mailmaga_flag'    => $data['mailmgzFlag'], // メルマガ経由フラグ
            'salesforce_flag'      => 0,
            'regist_date'          => $now,
            'update_date'          => $now,
        ];

        $id = DB::table($this->table)->insertGetId($params);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }
    
    /**
     * 黒本LP経由マッチング登録
     *
     * @access public
     * @param array $data
     * @return mixed integer id 登録できたら id を返す / boolean false：失敗
     */
    public function registWoaMatchingFromKurohon($data)
    {
        $now = new Carbon();
        $params = [
            'customer_id'        => $data['user'],
            'order_salesforce_id'  => $data['orderId'],
            'entry_status'         => $data['entryStatus'], // 受注までのステータス（0:求人エントリー，1:求人詳細問合せ）
            'action'               => $data['action'],
            'action_first'         => $data['action_first'],
            'via_mailmaga_flag'    => $data['mailmgzFlag'], // メルマガ経由フラグ
            'salesforce_flag'      => 0,
            'regist_date'          => $now,
            'update_date'          => $now,
        ];

        $id = DB::table($this->table)->insertGetId($params);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }
    /**
     * SF連携対象マッチングを取得
     *
     * @param string $id ID
     * @return Collection $result SF連携対象求職者
     */
    public function getSfLinkData($id = null)
    {
        $builder = DB::table($this->table)
            ->select(
                'id',
                'salesforce_id',
                'order_salesforce_id',
                'customer_id',
                'entry_status',
                'action',
                'action_first',
                'via_mailmaga_flag',
                'update_date',
            );

        if (!empty($id)) {
            // 即時連携用
            $result = $builder
                ->where([
                    ['id', $id],
                    ['salesforce_flag', 0],
                    ['delete_flag', 0],
                ])
                ->get();
        } else {
            // 定期連携用
            $result = $builder
                ->whereIn('salesforce_flag', [0, 2])
                ->whereRaw('regist_date < (NOW() - INTERVAL 5 MINUTE)')
                ->where('delete_flag', 0)
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
        $update = ['salesforce_flag' => $sfFlag];

        $result = $this
            ->where('id', $id)
            ->update($update);

        return $result;
    }
}
