<?php
/**
 *  FeedLpManager
 */

namespace App\Managers;

use Illuminate\Support\Facades\DB;

class FeedLpManager
{
    /**
     * フィードLP毎のABパターンを返す
     * @param string $feedId
     * @return String ABパターン
     */
    public function getABTestPattern($feedId)
    {
        // パターン(デフォルトA)
        $result = 'A';
        try {
            // feedIdに紐づくFeedLPデータ取得
            $feedLPInfo = $this->getFeedLPInfoByFeedId($feedId);
            if (empty($feedLPInfo)) {
                // 取得できない場合、Aデフォルト値を返す
                return $result;
            }
            switch ($feedLPInfo->ab_test) {
                case '0':
                    // Aをセット
                    $result = 'A';
                    break;
                case '1':
                    // Bをセット
                    $result = 'B';
                    break;
                case '2':
                    // AorBを取得
                    $result = (new UtilManager())->getScreen();
                    break;
                default:
                    // Aをセット
                    $result = 'A';
                    break;
            }
        } catch (\Exception $e) {
            return false;
        }

        return $result;
    }

    /**
     * master_feed_lpからsite_idに紐づく取得処理
     * @param string $feedId
     * @return feedIdに紐づくLP情報
     */
    private function getFeedLPInfoByFeedId($feedId)
    {
        $result = DB::table('master_feed_lp')
            ->where('feed_id', $feedId)
            ->where('delete_flag', 0)
            ->first();

        return $result;
    }
}
