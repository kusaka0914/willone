<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StaffExample extends Model
{
    protected $table = "staff_examples";
    protected $guarded = ['id'];
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    protected static function booted()
    {
        static::addGlobalScope('defaultCondition', function (Builder $builder) {
            $builder->where('delete_flag', 0)
                ->orderBy('regist_date', 'asc');
        });
    }
}
