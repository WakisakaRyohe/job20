<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait SessionTrait
{
    public static function getSession(Request $request)
    {
        return [
            'auth_key' => $request->session()->get('auth_key'),
            'email' => $request->session()->get('email'),
            'old_email' => $request->session()->get('old_email'),
            'new_email' => $request->session()->get('new_email'),
            'limit' => $request->session()->get('limit'),
        ];
    }

    public static function forgetSesion(Request $request)
    {
        // セッション削除
        // $request->session()->flush()で全データ削除すると、tokenも消えてエラーになる
        $request->session()->forget('auth_key');
        $request->session()->forget('email');
        $request->session()->forget('old_email');
        $request->session()->forget('new_email');
        $request->session()->forget('limit');

        // セッション削除の確認
        $session_auth_key_delete_flg = !($request->session()->exists('auth_key'));
        $session_email_delete_flg = !($request->session()->exists('email'));
        $session_old_email_delete_flg = !($request->session()->exists('old_email'));
        $session_new_email_delete_flg = !($request->session()->exists('new_email'));
        $session_limit_delete_flg = !($request->session()->exists('limit'));

        if ($session_auth_key_delete_flg && $session_old_email_delete_flg && 
            $session_new_email_delete_flg && $session_limit_delete_flg) {
            Log::info('セッション削除成功。');
            Log::info('認証キー削除結果：' . $session_auth_key_delete_flg);
            Log::info('メールアドレス削除結果：' . $session_email_delete_flg);
            Log::info('古いメールアドレス削除結果：' . $session_old_email_delete_flg);
            Log::info('新しいメールアドレス削除結果：' . $session_new_email_delete_flg);
            Log::info('有効期限の削除結果：' . $session_limit_delete_flg);
        }else{
            Log::error('セッション削除失敗。'); 
        }
    }
}