<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // 変更可能カラム
    protected $guarded = array('id');

    // 指定カラムの初期値を設定
    protected $attributes = [
        "company_icon_image" => "company_default.jpg",
    ];
    
    // １対多リレーション（返り値はコレクション型）
    // 求人一覧を取得
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
    // 掲示板一覧を取得
    public function bords()
    {
        return $this->hasMany('App\Bord');
    }
    
    // 多対１リレーション（返り値はモデル型）
    public function industry()
    {
        return $this->belongsTo('App\Industry');
    }

    // 多対多リレーション
    // 掲示板を介してユーザー情報を取得
    public function usersInBord()
    {
        return $this->belongsToMany('App\User', 'bords', 'company_id', 'user_id');
    }
    // 掲示板を介して求人情報を取得
    public function jobsInBord()
    {
        return $this->belongsToMany('App\Job', 'bords', 'company_id', 'job_id');
    }
}
