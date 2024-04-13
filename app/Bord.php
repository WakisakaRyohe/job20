<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bord extends Model
{
    // 変更可能カラム
    protected $fillable = [
        'user_id', 'company_id',
    ];

    // バリデーションルール
    public static $rules = array(
        'user_id' => 'required|integer',
        'company_id' => 'required|integer',
    );
    
    // １対多リレーション（返り値はコレクション型）
    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    // 多対１リレーション
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function job()
    {
        return $this->belongsTo('App\Job');
    }
}
