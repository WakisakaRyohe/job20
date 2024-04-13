<?php
namespace App\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionClass
{
    public static function forget(Request $request)
    {
        // セッション削除
        $request->session()->forget('auth_key');
        $request->session()->forget('old_email');
        $request->session()->forget('new_email');
        $request->session()->forget('limit');

        // セッション削除の確認
        $session_auth_key_delete_flg = !($request->session()->exists('auth_key'));
        $session_old_email_delete_flg = !($request->session()->exists('old_email'));
        $session_new_email_delete_flg = !($request->session()->exists('new_email'));
        $session_limit_delete_flg = !($request->session()->exists('limit'));

        if ($session_auth_key_delete_flg && $session_old_email_delete_flg && 
            $session_new_email_delete_flg && $session_limit_delete_flg) {
            Log::info('セッション削除成功。');
            Log::info('認証キー削除結果：' . $session_auth_key_delete_flg);
            Log::info('古いメールアドレス削除結果：' . $session_old_email_delete_flg);
            Log::info('新しいメールアドレス削除結果：' . $session_new_email_delete_flg);
            Log::info('有効期限の削除結果：' . $session_limit_delete_flg);
        }else{
            Log::error('セッション削除失敗。'); 
        }
    }
}