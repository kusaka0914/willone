<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FriendReferral extends Model
{
    const CREATED_AT = 'regist_date';
    const UPDATED_AT = 'update_date';

    protected $table = 'friend_referral';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
