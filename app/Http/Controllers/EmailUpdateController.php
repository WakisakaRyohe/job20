<?php

namespace App\Http\Controllers;

use App\User;
use App\Library\SessionClass;
use App\Mail\UpdateEmail;
use App\Traits\SessionTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;        

class UpdateEmailController extends Controller
{    
    use SessionTrait;

    // メールアドレス変更完了の画面表示処理
    public function __invoke(Request $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;
        $unAuthFlg= false;
        $limitOverFlg= false;

        try {
            Log::info('メールアドレス変更完了の画面表示処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();
            $userCount = User::where('id', $user->id)->count();

            // ユーザーデータ取得の判定
            if($userCount === 1){
                Log::info('ユーザーデータ取得成功。');
        
                // セッション情報を取得
                $session = $this->getSession($request);

                if ($session['auth_key'] && $session['old_email'] && $session['new_email']) {
                    Log::info('認証キー：' . $session['auth_key'] . '、古いメールアドレス：' . $session['old_email'] . '、新しいメールアドレス：' . $session['new_email']);
 
                    // 有効期限の検証用に現在時刻を変数に格納
                    if($request->limitOverFlg === true){
                        $now = Carbon::now()->addhour(5)->timestamp;
                    }else{
                        $now = Carbon::now()->timestamp;
                    }
                    
                    if($session['limit'] > $now){
                        Log::info('認証キーの有効期限内です。');
                        Log::info('有効期限：' . $session['limit'] . '、現在時刻：' . $now);

                        $new_email = $session['new_email'];
                        $emailCount = User::where('email', $new_email)->count();

                        if($emailCount === 0){
                            Log::info('未登録のメールアドレスです。');

                            // メールアドレスをセット
                            $user->email = $session['new_email'];

                            // メールアドレス変更処理できたらtrue
                            $updateUserFlg = $user->save();
                            $updateUserCount = $user->where('email', $session['new_email'])->count();

                            if($updateUserFlg === true && $updateUserCount === 1){
                                Log::info('メールアドレス変更処理成功。');

                                // 完了メール送信
                                $params = [
                                    'name' => $user->name,
                                    'old_email' => $session['old_email'],
                                    'new_email' => $session['new_email'],
                                ];
                                Mail::to($session['old_email'])->send(new UpdateEmail($params));        
                                Mail::to($session['new_email'])->send(new UpdateEmail($params));

                                if (count(Mail::failures()) == 0) {
                                    Log::info('新旧アドレスに完了メール送信成功。');
                                    $successFlg = true;

                                }else{
                                    Log::error('新旧アドレスに完了メール送信失敗。'); 
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
            Log::error('メールアドレス変更完了の画面表示処理失敗。error_message = ' . $e);
        }

        // セッション削除
        $this->forgetSesion($request);
        // SessionClass::forget($request);
        
        Log::info('メールアドレス変更完了の画面表示処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
            'unAuthFlg'=> $unAuthFlg,
            'limitOverFlg' => $limitOverFlg,
        ]);
    }    
}
