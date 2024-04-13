<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // 変更可能カラム
    protected $fillable = [
        'bord_id', 'sender_id', 'receiver_id', 'message'
    ];

    // 指定カラムの初期値を設定
    protected $attributes = [
        "user_flg" => true,
    ];
    
    // バリデーションルール
    public static $rules = array(
        'bord_id' => 'required|integer',
        'sender_id' => 'required|string',
        'receiver_id' => 'required|string',
        'message' => 'required|string|max:1000',
    );
    
    // 多対１リレーション
    public function bord()
    {
        return $this->belongsTo('App\Bord');
    }
}
