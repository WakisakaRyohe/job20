<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\ChangeEmailRequest;
use App\Mail\ConfirmEmail;
use App\Mail\UpdateEmail;
use App\Traits\SessionTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ChangeEmailController extends Controller
{
    use SessionTrait;

    // メールアドレス変更画面の表示
    public function edit()
    {
        // 最後にreturnするデータを定義
        $email = null;

        try {
            Log::info('メールアドレス変更画面の表示処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                $email = $user->email;

                if(!empty($email)){
                    Log::info('メールアドレス：' . $email);
        
                }else{
                    Log::error('メールアドレス取得失敗。'); 
                }  
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('メールアドレス変更画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('メールアドレス変更画面の表示処理終了。');

        return response()->json([
            'email' => $email,
        ]);
    }

    // メールアドレス認証メール送信処理
    public function update(ChangeEmailRequest $request)
    {
        // 最後にreturnするデータを定義
        $session = null;

        try {
            Log::info('メールアドレス認証メール送信処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                $email_new = $request->email_new;
                Log::info('メールアドレス：' . $email_new);

                // メールアドレス確認メール送信
                Mail::to($email_new)->send(new ConfirmEmail());        

                if (count(Mail::failures()) == 0) {
                    Log::info('メールアドレス認証メール送信成功。');

                    //認証に必要な情報をセッションへ保存（有効期限は30分）
                    $request->session()->put('email', $email_new);
                    $request->session()->put('limit', time()+(60*30));

                    // セッション情報を取得
                    $session = $this->getSession($request);

                    if ($session['email'] && $session['limit']) {
                        Log::info('セッションのメールアドレス：' . $session['email']);
                        Log::info('セッションの有効期限：' . $session['limit']);
    
                    }else{
                        Log::error('認証情報をセッションへ保存失敗。'); 
                    }
                }else{
                    Log::error('メールアドレス認証メール送信失敗。'); 
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('メールアドレス認証メール送信処理失敗。error_message = ' . $e);
        }

        Log::info('メールアドレス認証メール送信処理終了。');

        return response()->json([
            'session' => $session,
        ]);
    }

    // メールアドレス変更処理
    public function complete(Request $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;
        $unAuthFlg= false;
        $limitOverFlg= false;

        try {
            Log::info('メールアドレス変更処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);
        
                // セッション情報を取得
                $session = $this->getSession($request);

                if ($session['limit'] && $session['email']) {
                    Log::info('セッションのメールアドレス：' . $session['email']);
                    Log::info('セッションの有効期限：' . $session['limit']);
 
                    // 有効期限の検証用に現在時刻を変数に格納
                    if($request->limitOverFlg === true){
                        $now = Carbon::now()->addhour(5)->timestamp;
                    }else{
                        $now = Carbon::now()->timestamp;
                    }
                    
                    if($session['limit'] > $now){
                        Log::info('認証キーの有効期限内です。');
                        Log::info('有効期限：' . $session['limit'] . '、現在時刻：' . $now);

                        $email = $session['email'];
                        $emailCount = User::where('email', $email)->count();

                        if($emailCount === 0){
                            Log::info('未登録のメールアドレスです。');

                            // メールアドレスをセット
                            $user->email = $session['email'];

                            // メールアドレス変更処理できたらtrue
                            $updateUserFlg = $user->save();
                            $updateUserCount = $user->where('email', $session['email'])->count();

                            if($updateUserFlg === true && $updateUserCount === 1){
                                Log::info('メールアドレス変更処理成功。');

                                // メールアドレス変更完了メール送信
                                Mail::to($session['email'])->send(new UpdateEmail());

                                if (count(Mail::failures()) == 0) {
                                    Log::info('新しいメールアドレスに変更完了メール送信成功。');
                                    $successFlg = true;

                                }else{
                                    Log::error('新しいメールアドレスに変更完了メール送信失敗。'); 
                                }
                            }else{
                                Log::error('メールアドレス変更処理失敗。'); 
                            }
                        }else{
                            Log::error('登録済みのメールアドレスです。');         
                        }
                    }else{
                        Log::error('認証キーの有効期限オーバーです。');
                        $limitOverFlg = true;
                    }
                }else{
                    Log::error('認証情報の取得失敗。'); 
                    $unAuthFlg= true;
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('メールアドレス変更処理失敗。error_message = ' . $e);
        }

        // セッション削除
        $this->forgetSesion($request);
        // SessionClass::forget($request);
        
        Log::info('メールアドレス変更処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
            'unAuthFlg'=> $unAuthFlg,
            'limitOverFlg' => $limitOverFlg,
        ]);
    }    
}
