<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MasterConsultantMst extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    protected $table = 'master_consultant_mst';

    protected static function booted()
    {
        static::addGlobalScope('defaultCondition', function (Builder $builder) {
            $builder->where('delete_flag', 0);
        });
    }

    public function findBySmsId(string $smsId): ?MasterConsultantMst
    {
        return $this->where([['status', '0'], ['sms_id', $smsId]])->first();
    }
}
