<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kaitou extends Model
{
    const DEL_FLG_ON = 1;
    const DEL_FLG_OFF = 0;

    protected $table = "kaitou";

    public function get($id)
    {
        return $this->onWriteConnection()->where('id', $id)->first();
    }

    public function getList()
    {
        return $this->onWriteConnection()->orderBy('shiken_date', 'desc')->get();
    }

    public function remove($id)
    {
        return $this->onWriteConnection()->where('id', $id)->delete();
    }

}
