<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    // １対多リレーション（返り値はコレクション型）
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
}
