<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class SettingController extends Controller
{
    public function __invoke()
    {
        // 最後にreturnするデータを定義
        $user = null;
        $successFlg= false;

        try {
            Log::info('各種設定画面の表示処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);
                $successFlg = true;
        
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('各種設定画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('各種設定画面の表示処理終了。');

        return response()->json([
            'user' => $user,
            'successFlg' => $successFlg,
        ]);
    }
}
