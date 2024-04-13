<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    // 変更可能カラム
    protected $guarded = array('id');

    // 指定カラムの初期値を設定
    protected $attributes = [
        "pickupFlg" => 0,
    ];
    
    // 多対１リレーション（返り値はモデル型）
    // 会社情報を取得
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    // 多対多リレーション
    // 掲示板を介してユーザー情報を取得
    public function usersInBord()
    {
        return $this->belongsToMany('App\User', 'bords', 'job_id', 'user_id');
    }
    // 掲示板を介して会社情報を取得
    public function companiesInBord()
    {
        return $this->belongsToMany('App\Company', 'bords', 'job_id', 'company_id');
    }
}
