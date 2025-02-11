<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HubSpotToken extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    protected $table = 'hubspot_token';
    protected $primaryKey = 'id'; // プライマリキーを指定

    /**
     * トークンの更新
     * @param array $tokens
     * @param string|null $code
     * @return bool
     */
    public function updateToken(array $tokens, ?string $code): bool
    {
        $recode = $this->first() ?? new self();
        if ($code) {
            $recode->authorization_code = $code;
        }

        $recode->access_token = $tokens['access_token'];
        $recode->refresh_token = $tokens['refresh_token'];
        // 現在日時に有効期限(秒)を加算して保存
        $recode->limit_date = date('Y-m-d H:i:s', strtotime('+' . $tokens['expires_in'] . ' seconds'));

        return $recode->save();
    }
}
