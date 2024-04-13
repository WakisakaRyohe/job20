<?php

namespace App\Http\Controllers;

use App\Bord;
use App\Company;
use App\Job;
use App\Message;
use App\User;
use App\Http\Requests\MessageRequest;
use App\Mail\newMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class BordController extends Controller
{
    public function index(Request $request, $id)
    {
        Log::info('連絡掲示板画面の表示処理開始。対象アクション：' . __METHOD__);

        // 最後にreturnするデータを定義
        $bord = Bord::with('user', 'company', 'job', 'messages')->find($id);
        $successFlg= false;
        
        // ポリシーはtry&catch内では使えないため、ここで認証
        $this->authorize('viewBord', $bord);

        try {              
            // 連絡掲示板データ取得の判定
            if(!empty($bord)){
                $messagesCount = $bord->messages->count();
                Log::info('連絡掲示板ID：' . $bord . '、ユーザーID：' . $bord->user->id . 
                '、求人ID：' . $bord->job->id . '、会社ID：' . $bord->company->id . '、メッセージ数：' . $messagesCount);

                $successFlg = true;

            }else{
                Log::error('連絡掲示板データ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('連絡掲示板画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('連絡掲示板画面の表示処理終了。');

        return response()->json([
            'bord' => $bord,
            'successFlg' => $successFlg,
        ]);    
    }

    // メッセージ新規投稿処理
    public function create(MessageRequest $request, $id)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;
        $message = null;

        try {
            Log::info('メッセージ新規投稿の処理開始。対象アクション：' . __METHOD__);

            // メッセージ情報をセット
            $message = new Message;
            $message->bord_id = $id;
            $message->sender_id = ($request->user_flg) ? $request->bord['user']['id'] : $request->bord['company']['id'] ;
            $message->receiver_id = ($request->user_flg) ? $request->bord['company']['id'] : $request->bord['user']['id'] ;
            // 会社のメッセージとして投稿する場合
            if(!$request->user_flg){
                $message->user_flg = false;
            }
            $message->message = $request->newMessage;

            $result = $message->save();
            Log::info('メッセージ投稿結果：' . $result);

            if($result){
                Log::info('メッセージ投稿する掲示板ID：' . $message->bord_id . 
                '、投稿したユーザーID：' . $request->bord['user']['id'] . 
                '、会社ID：' . $request->bord['company']['id'] . 
                '、求人ID：' . $request->bord['job']['id']);
                Log::info('メッセージ内容：' . $message->message);
    
                // メッセージ相手にメール送信
                if($request->user_flg){
                    Log::info('ユーザーのメッセージ投稿なので、会社にメール送信しません。');
                    $successFlg = true;

                }else{
                    Log::info('会社のメッセージ投稿なので、ユーザーにメール送信します。');

                    // メールで使う情報を変数に格納
                    $params = [
                        'sender_name' => $request->bord['company']['company_name'],
                        'bord_id' => $request->bord['id'],
                        'message' => $message->message,
                    ];

                    // メール送信
                    Mail::to($request->bord['user']['email'])->send(new newMessage($params));
                    // テスト送信
                    // Mail::to('kokuto160@gmail.com')->send(new newMessage($params));

                    if (count(Mail::failures()) == 0) {
                        Log::info('ユーザーにメール送信成功。');
                        $successFlg = true;
    
                    }else{
                        Log::error('ユーザーにメール送信失敗。'); 
                    }
                }
            }else{
                Log::error('メッセージ投稿失敗。'); 
            }   
        } 
        catch(Exception $e) {
            Log::error('メッセージ新規東欧の処理失敗。error_message = ' . $e);
        }

        Log::info('メッセージ新規投稿の処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
            'message' => $message,
        ]);
    }    
}
