<?php
namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RegistrationInquiryDetail extends Model
{
    protected $table = "registration_inquiry_detail";

    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    const FLAG_ON = 1; // フラグ：ON
    const FLAG_OFF = 0; // フラグ：OFF

    /**
     * 過去2週間から現在までの問い合わせデータを取得
     *
     * @param string $templateId
     * @return Collection
     */
    public function getInquiryData(string $templateId): Collection
    {
        return $this->where('inquiry_date', '<=', Carbon::now())
        ->where('delete_flag', self::FLAG_OFF)
        ->where('template_id', $templateId)
        ->orderBy('inquiry_date', 'desc')
        ->get();
    }
}
