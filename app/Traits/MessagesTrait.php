<?php
namespace App\Traits;

use App\Message;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait MessagesTrait
{
    public static function getMessages()
    {
        $user = Auth::user();

        // 掲示板取得し、メッセージ配列を定義
        $bords = $user->bords()->with('job', 'company', 'messages')->orderBy('created_at', 'desc')->get();
        $messages = [];

        if(!empty($bords)){
            foreach($bords as $bord){
                // 受信メッセージ
                $received_message = Message::where('bord_id', $bord['id'])
                    ->where('receiver_id', $user['id'])
                    ->orderBy('created_at', 'desc')->first();
                Log::info('受信メッセージID：' . $received_message['id']);

                // 最新メッセージ        
                $latest_message = Message::where('bord_id', $bord['id'])
                    ->orderBy('created_at', 'desc')->first();
                Log::info('最新メッセージID：' . $latest_message['id']);

                // 返信していない場合（受信メッセージが最新）                       
                if($received_message['id'] === $latest_message['id']){
                    Log::info('受信メッセージが最新です。');

                    // 会社情報と求人名を取得して、メッセージに値を追加
                    $company = Company::where('id', '=', $received_message['sender_id'])->first();
                    $received_message['company_name'] = $company['company_name'];
                    $received_message['company_icon_image'] = $company['company_icon_image'];
                    $received_message['job_name'] = $bord['job']['job_name'];

                    // 配列に追加
                    array_push($messages, $received_message);
                }

                // 指定カラムを抜き出して配列化
                $created_at_array = array_column($messages, 'created_at');
                // 指定カラムで配列を降順に並べ替え
                array_multisort($created_at_array, SORT_DESC, $messages);
            }                
        } 
        
        return $messages;
    }
}