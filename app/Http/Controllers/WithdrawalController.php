<?php

namespace App\Http\Controllers;

use App\Bord;
use App\User;
use App\Http\Requests\WithdrawalRequest;
use App\Mail\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Exception;

class WithdrawalController extends Controller
{
    // 退会処理（レコード削除）
    public function __invoke(WithdrawalRequest $request)
    {
        // 最後にreturnするデータを定義
        $successFlg= false;

        try {
            Log::info('退会処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);

                $profile = $user->profile;
                Log::info('プロフィール登録判定：' . $profile);

                // 証明写真をアップロードしていた場合は削除
                if ($profile !== null && $profile->id_photo !== 'user_icon.png') {
                    Log::info('証明写真をアップロードしているので、削除します。');
                    Log::info('画像パス：' . $profile->id_photo);

                    Storage::disk('s3')->delete('/job20/' . $profile->id_photo);
                    $imageDeleteFlg = !(Storage::disk('s3')->exists('job20/' . $profile->id_photo));

                    // deleteメソッドが使える場合
                    // Storage::disk('s3')->delete('/job_change_20/' . $profile->id_photo);
                    // $imageDeleteFlg = !(Storage::disk('s3')->exists('job_change_20/' . $profile->id_photo));

                    if($imageDeleteFlg){
                        Log::info('証明写真の削除成功。結果：' . $imageDeleteFlg);

                    }else{
                        Log::error('証明写真の削除失敗。退会処理終了。');

                        return response()->json([
                            'successFlg' => $successFlg,
                        ]);                
                    }
                }else{
                    Log::info('証明写真をアップロードしていません。');
                }

                // ユーザーデータ削除
                $user->delete();
                $userDeleteFlg = User::where('id', $user->id)->count();

                if($userDeleteFlg === 0){
                    Log::info('ユーザーデータ削除成功。');

                    // ユーザーにメール送信
                    Mail::to($user->email)->send(new Withdrawal());

                    if (count(Mail::failures()) == 0) {
                        Log::info('メール送信成功。');
                        $successFlg= true;

                    }else{
                        Log::error('メール送信失敗。');
                    }
                }else{
                    Log::error('ユーザーデータ削除失敗。');
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('退会処理失敗。error_message = ' . $e);
        }

        Log::info('退会処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
        ]);
    }
}
