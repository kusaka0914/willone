<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = "staff";

    public function list(): Collection
    {

        return $this->where('del_flg', 0)->where('type', 1)->get();
    }
}
