<?php

namespace App\Model;

use App\Managers\SlackServiceManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class WoaCustomerTrackingMapping extends Model
{
    const COOKIE_KEY = 'user_id';
    const COOKIE_EXPIRES_USER_ID = 60 * 24 * 365; // 1年間

    protected $fillable = [
        'customer_id',
        'customer_id_hash',
        'upload_status',
    ];

    /**
     * @param string $customerId
     * @return void
     */
    public function createWithCookie(string $customerId): void
    {
        if (Cookie::has(self::COOKIE_KEY) || empty($customerId)) {
            Log::info('customerIdが空、または既にCookieが設定されているため、処理をスキップします', [
                'customerId' => $customerId,
            ]);

            return;
        }
        try {
            $tracking = self::firstOrCreate(
                [
                    'customer_id' => $customerId,
                ],
                [
                    // パスワードと違って漏洩したときのインパクトが低いため、マッピングのハッシュはコストをすこし緩める
                    'customer_id_hash' => Hash::make($customerId, ['rounds' => 10]),
                    'upload_status'    => config('ini.UPLOAD_STATUS')['pending'],
                ]
            );
            Cookie::queue(self::COOKIE_KEY, $tracking->customer_id_hash, self::COOKIE_EXPIRES_USER_ID);
        } catch (\Throwable $e) {
            // slackにも通知する
            (new SlackServiceManager)->channel('web_customer_tracking_mapping_error')->send($e->getMessage());

            // クリティカルじゃないので一旦エラーログを出して後続処理を実行
            Log::warning("woa_customer_tracking_mappingsレコード作成失敗", [
                'customerId' => $customerId,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
