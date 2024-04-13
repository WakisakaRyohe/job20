<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // 変更可能カラム
    protected $guarded = array('id');

    // 指定カラムの初期値を設定
    protected $attributes = [
        'id_photo' => 'user_icon.png',
    ];
    
    // １対１リレーション（返り値はモデル型）
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
