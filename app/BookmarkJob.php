<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookmarkJob extends Model
{
    // 変更可能カラム
    protected $fillable = [
        'user_id', 'job_id',
    ];

    // バリデーションルール
    public static $rules = array(
        'user_id' => 'required|integer',
        'job_id' => 'required|integer',
    );
    
    // 多対１リレーション
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function job()
    {
        return $this->belongsTo('App\Job');
    }
}
