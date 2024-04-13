<?php

namespace App\Http\Controllers;

use App\Company;
use App\Message;
use App\User;
// use App\Traits\MessagesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class MessagesController extends Controller
{
    // use MessagesTrait;

    public function __invoke(Request $request)
    {
        // 最後にreturnするデータを定義
        $bords = null;
        $successFlg= false;

        try {
            Log::info('メッセージ一覧表示処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                // 掲示板取得
                $bords = $user->bords()->with('job', 'company', 'messages')->orderBy('created_at', 'desc')->get();
                
                if(!empty($bords)){
                    Log::info('掲示板数：' . count($bords));
                    $successFlg= true;

                }else{
                    Log::info('掲示板はありません。'); 
                }                
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('メッセージ一覧表示処理失敗。error_message = ' . $e);
        }

        Log::info('メッセージ一覧表示処理終了。');

        return response()->json([
            'bords' =>  $bords,
            'successFlg' => $successFlg,
        ]);
    }
}
