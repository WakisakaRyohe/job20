<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentStatus extends Model
{
    // １対多リレーション（返り値はコレクション型）
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
}
