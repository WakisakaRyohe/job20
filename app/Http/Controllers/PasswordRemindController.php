<?php

namespace App\Http\Controllers;

use App\User;
use App\UserToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendEmailRequest;
use App\Mail\PasswordRemindSend;
use App\Mail\PasswordRemindReceive;
use App\Traits\SessionTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Exception;

class PasswordRemindController extends Controller
{
    use SessionTrait;

    // パスワード再設定メール送信処理
    public function send(SendEmailRequest $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;

        try {
            Log::info('パスワード再設定メール送信処理開始。対象アクション：' . __METHOD__);

            //認証キー生成
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
            $auth_key = '';
            for ($i = 0; $i < 8; ++$i) {
                $auth_key .= $chars[mt_rand(0, 61)];
            }
            
            if(strlen($auth_key) === 8){
                Log::info('認証キー生成成功。認証キー：' . $auth_key);
                
                // メール送信
                Mail::to($request->email)->send(new PasswordRemindSend($auth_key));

                if (count(Mail::failures()) == 0) {
                    Log::info('パスワード再発行メール送信成功。');

                    //認証に必要な情報をセッションへ保存（有効期限は30分）
                    if($request->testFlg === true){
                        $request->session()->put('auth_key', 'test_auth_key');
                    }else{
                        $request->session()->put('auth_key', $auth_key);
                    }
                    $request->session()->put('email', $request->email);
                    $request->session()->put('limit', time()+(60*30));

                    $session_auth_key = $request->session()->get('auth_key');
                    $session_email = $request->session()->get('email');
                    $session_limit = $request->session()->get('limit');

                    if ($session_auth_key && $session_email && $session_limit) {
                        Log::info('認証情報をセッションへ保存成功。');
                        Log::info('認証キー：' . $session_auth_key);
                        Log::info('メールアドレス：' . $session_email);
                        Log::info('有効期限：' . $session_limit);
                        
                        $successFlg = true;

                    }else{
                        Log::error('認証情報をセッションへ保存失敗。'); 
                    }
                }else{
                    Log::error('パスワード再発行メール送信失敗。'); 
                }
            }else{
                Log::error('認証キー生成失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('パスワード再設定メール送信処理失敗。error_message = ' . $e);
        }

        Log::info('パスワード再設定メール送信処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
        ]);
    }
        
    // パスワード再発行認証の画面表示処理
    public function edit(Request $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;
        $session_auth_key = null;
        $limitOverFlg= false;

        try {
            Log::info('パスワード再発行認証の画面表示処理開始。対象アクション：' . __METHOD__);

            $session_auth_key = $request->session()->get('auth_key');

            if($session_auth_key){
                Log::info('認証キー：' . $session_auth_key);
                $successFlg = true;

            }else{
                Log::error('認証キー取得失敗。'); 
                $limitOverFlg= true;

                // セッション削除
                $this->forgetSesion($request);
            }
        } 
        catch(Exception $e) {
            Log::error('パスワード再発行認証の画面表示処理失敗。error_message = ' . $e);

            // セッション削除
            $this->forgetSesion($request);
        }

        Log::info('パスワード再発行認証の画面表示処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
            'session_auth_key' => $session_auth_key,
            'limitOverFlg' => $limitOverFlg,
        ]);
    }

    // パスワード更新処理
    public function update(ResetPasswordRequest $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;
        $limitOverFlg = false;

        try {
            Log::info('パスワード更新処理開始。対象アクション：' . __METHOD__);

            $session_email = $request->session()->get('email');
            $session_limit = $request->session()->get('limit');

            if ($session_email && $session_limit) {
                Log::info('メールアドレス：' . $session_email);
                Log::info('有効期限：' . $session_limit);

                // ユーザーの存在確認
                $user = User::where('email', $session_email)->first();
                $userCountNum = User::where('email', $session_email)->count();

                // ユーザーが存在する場合
                if ($userCountNum === 1) {
                    Log::info('ユーザーのレコード数：' . $userCountNum);

                    // 有効期限の検証用に現在時刻を変数に格納
                    if($request->limitOverFlg === true){
                        $now = Carbon::now()->addhour(1)->timestamp;
                    }else{
                        $now = Carbon::now()->timestamp;
                    }

                    if ($session_limit > $now) {
                        Log::info('認証キーの有効期限内です。');
                        Log::info('有効期限：' . $session_limit . '、現在時刻：' . $now);
                        
                        //パスワード生成
                        if($request->testFlg === true){
                            $pass = 'newPassword';
                        }else{
                            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
                            $pass = '';
                            for ($i = 0; $i < 8; ++$i) {
                                $pass .= $chars[mt_rand(0, 61)];
                            }    
                        }
                        Log::info('生成したパスワード：' . $pass);

                        // 古いパスワードを取得
                        $old_password = $user->password;

                        // 生成したパスワードに更新して、新しいパスワードを取得
                        $user->update(['password' => Hash::make($pass)]);
                        $new_password = $user->password;

                        // パスワード更新成功の場合
                        if ($old_password !== $new_password) {
                            Log::info('パスワード更新成功。');
                            Log::info('古いパスワード：' . $old_password);
                            Log::info('新しいパスワード：' . $new_password);

                            // メール送信
                            Mail::to($session_email)->send(new PasswordRemindReceive($pass));

                            if (count(Mail::failures()) == 0) {
                                Log::info('パスワード再発行メール送信成功。');
                            
                                $successFlg= true;

                            }else{
                                Log::error('パスワード再発行メール送信失敗。'); 
                            }
                        }else{
                            Log::error('パスワード更新失敗。'); 
                        }
                    }else{
                        Log::error('認証キーの有効期限オーバーです。');
                        $limitOverFlg = true;
                    }
                }else{
                    Log::info('ユーザーが存在しません。');
                }
            }else{
                Log::error('認証情報をセッションから取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('パスワード更新処理失敗。error_message = ' . $e);
        }

        // セッション削除
        $this->forgetSesion($request);

        Log::info('パスワード更新処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
            'limitOverFlg' => $limitOverFlg,
        ]);
    }
}
