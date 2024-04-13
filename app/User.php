<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\UserToken;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // 変更可能カラム
    protected $guarded = array('id', 'file', 'testFlg');

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 指定カラムの初期値を設定
    protected $attributes = [
        // "" => "",
    ];
    
    // １対１リレーション（返り値はモデル型）
    // プロフィールを取得
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }
    // スキルを取得
    public function skill()
    {
        return $this->hasOne('App\Skill');
    }
    // 自己PRを取得
    public function self_promotion()
    {
        return $this->hasOne('App\SelfPromotion');
    }

    // １対多リレーション（返り値はコレクション型）
    // 職務経歴を取得
    public function job_careers()
    {
        return $this->hasMany('App\JobCareer');
    }
    // 掲示板一覧を取得
    public function bords()
    {
        return $this->hasMany('App\Bord');
    }
    
    // 多対多リレーション
    // 応募した求人一覧を取得
    public function appliedJobs()
    {
        return $this->belongsToMany('App\Job', 'applied_jobs', 'user_id', 'job_id');
    }
    // 気になる求人一覧を登録日時付きで取得
    public function bookmarkJobs()
    {
        return $this->belongsToMany('App\Job', 'bookmark_jobs', 'user_id', 'job_id')->withPivot('created_at');
    }
    // 掲示板を介して会社情報を取得
    public function companiesInBord()
    {
        return $this->belongsToMany('App\Company', 'bords', 'user_id', 'company_id');
    }
    // 掲示板を介して求人情報を取得
    public function jobsInBord()
    {
        return $this->belongsToMany('App\Job', 'bords', 'user_id', 'job_id');
    }

    // その他
    // 気になる求人テーブルに日時付きで登録
    public function storeBookmarkJobs()
    {
        return $this->belongsToMany('App\Job', 'bookmark_jobs', 'user_id', 'job_id')->withPivot('created_at');
    }    
    // 応募済み求人テーブルに登録日時付きで登録
    public function storeAppliedJobs()
    {
        return $this->belongsToMany('App\Job', 'applied_jobs', 'user_id', 'job_id')->withPivot('created_at');
    }    
    // 気になる求人の存在確認
    public function isBookmark($id)
    {
        return $this->bookmarkJobs()->where('job_id', $id)->exists();
    }
    // 応募済み求人の存在確認
    public function isApplied($id)
    {
        return $this->appliedJobs()->where('job_id', $id)->exists();
    }
}
