<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;

        try {
            Log::info('お問い合わせ処理開始。対象アクション：' . __METHOD__);

            // メールで使う情報を変数に格納
            $params = [
                'name' => $request->name,
                'type' => $request->type,
                'subject' => $request->subject,
                'message' => $request->message,
            ];
            
            // メール送信
            Mail::to($request->email)->send(new Contact($params));

            if (count(Mail::failures()) == 0) {
                Log::info('メール送信成功。');
                $successFlg= true;

            }else{
                Log::error('メール送信失敗。');
            }
        } 
        catch(Exception $e) {
            Log::error('お問い合わせ処理失敗。error_message = ' . $e);
        }

        Log::info('お問い合わせ処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
        ]);
    }
}
