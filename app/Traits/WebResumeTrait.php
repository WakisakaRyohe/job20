<?php
namespace App\Traits;

use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait WebResumeTrait
{
    public static function getWebResume()
    {
        Log::info('WEB履歴書の取得開始。対象アクション：' . __METHOD__);

        // 最後にreturnするデータを定義
        $user = null;
        $hasWebResume = false;
        $successFlg = false;
        
        try {
            $id = Auth::id();
            $user = User::with('profile', 'job_careers','skill', 'self_promotion')->find($id);

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                // プロフィール登録済みの場合
                if(!empty($user->profile)){
                    Log::info('プロフィールID：' . $user->profile->id);
                }else{
                    Log::info('プロフィール未登録です。');
                }

                // 職務経歴登録済みの場合
                $job_careers = $user->job_careers->count();
                if($job_careers > 0){
                    Log::info('職務経歴数：' . $job_careers);
                }else{
                    Log::info('職務経歴未登録です。');
                }

                // 資格・スキル登録済みの場合
                if(!empty($user->skill)){
                    Log::info('スキルID：' . $user->skill->id);

                    $certificationsName = $user->skill->certifications;
                    Log::info('資格の文字列：' . $user->skill->certifications);

                    // 資格が未登録の場合
                    if( $certificationsName === '' || $certificationsName === null) {
                        $newArray = [];
                        
                    // 資格が１つの場合(資格の文字列に「,」が含まれていない)
                    }else if( ! str_contains($certificationsName, ',') ) {
                        $newArray = [];
                        array_push($newArray, $certificationsName);

                    // 資格が複数の場合
                    }else{
                        // 複数の資格が「」区切りの文字列でDBに保存されているので、配列に変換
                        $newArray = explode(",", $user->skill->certifications);
                    }

                    Log::info('資格数：'. count($newArray));
                    
                    // 配列化した資格の文字列をセット
                    $user->skill->certifications = $newArray;

                }else{
                    Log::info('資格・スキル未登録です。');
                }

                // 自己PR登録済みの場合
                if(!empty($user->self_promotion)){
                    Log::info('自己PRのID：' . $user->self_promotion->id);
                }else{
                    Log::info('自己PR未登録です。');
                }

                // WEB履歴書を全て登録している場合
                if(!empty($user->profile) && $job_careers > 0 && 
                   !empty($user->skill) && !empty($user->self_promotion)){
                    Log::info('WEB履歴書を全て登録');
                    $hasWebResume = true;
                    
                }else{
                    Log::info('WEB履歴書を全て登録していません。');
                }
                
                $successFlg = true;

            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('WEB履歴書の取得失敗。error_message = ' . $e);
        }

        Log::info('WEB履歴書の取得終了。');

        return [
            'user' => $user,
            'hasWebResume' => $hasWebResume,
            'successFlg' => $successFlg,
        ];
    }
}