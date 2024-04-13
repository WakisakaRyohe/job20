<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class ChangePasswordController extends Controller
{
    // パスワード変更画面の表示
    public function edit()
    {
        // 最後にreturnするデータを定義
        $password = null;
        $successFlg = false;

        try {
            Log::info('パスワード変更画面の表示処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                $password = $user->password;

                if(!empty($password)){
                    Log::info('パスワード：' . $password);
                    $successFlg = true;

                }else{
                    Log::error('パスワード取得失敗。'); 
                }  
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('パスワード変更画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('パスワード変更画面の表示処理終了。');

        return response()->json([
            'password' => $password,
            'successFlg' => $successFlg,
        ]);
    }

    // パスワードチェック
    public function check(Request $request)
    {
        // 最後にreturnするデータを定義
        $result= false;
        $successFlg = false;

        try {
            $result = password_verify($request->password_old, $request->password_db);
            $successFlg = true;
            Log::info('パスワードチェック結果:' . $result);
        } 
        
        catch(Exception $e) {
            Log::error('パスワードチェック処理失敗。error_message = ' . $e);
        }

        return response()->json([
            'result' => $result,
            'successFlg' => $successFlg,
        ]);
    }
    
    // パスワード変更処理
    public function update(ChangePasswordRequest $request)
    {
        // 最後にreturnするデータを定義
        $successFlg = false;

        try {
            Log::info('パスワード変更処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                // 新しいパスワードをハッシュ化してセット
                $user->password = Hash::make($request->password_new);
                Log::info('ハッシュ化した新しいパスワード：' . $user->password);

                // パスワード変更処理できたらtrue
                $successFlg = $user->save();
                $updateUserCount = $user->where('id', $user->id)->count();

                if($successFlg === true && $updateUserCount === 1){
                    Log::info('パスワード変更処理成功。');

                }else{
                    Log::error('パスワード変更処理失敗。'); 
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('パスワード変更処理失敗。error_message = ' . $e);
        }

        Log::info('パスワード変更処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
        ]);
    }     
}
