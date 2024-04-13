<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCareer extends Model
{
    // 変更可能カラム
    protected $guarded = array('id');

    // １対１リレーション（返り値はモデル型）
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
