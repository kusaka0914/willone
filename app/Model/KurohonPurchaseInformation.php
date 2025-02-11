<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * kurohonPurchaseInformation(黒本購入者情報)のModelクラス
 */
class KurohonPurchaseInformation extends Model
{
    protected $table = "kurohon_purchase_information";
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';
}
