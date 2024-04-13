<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Http\Requests\EditProfileRequest;
use Exception;
use InterventionImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{        
    // プロフィール編集画面の表示
    public function edit()
    {
        // 最後にreturnするデータを定義
        $profile = null;
        $editFlg = false;
        $successFlg = false;

        try {
            Log::info('プロフィール編集画面の表示処理開始。対象アクション：' . __METHOD__);

            $profile = Auth::user()->profile;

            // プロフィールデータ取得の判定
            if(!empty($profile)){
                $editFlg = true;
                Log::info('プロフィールID：' . $profile->id . '、$editFlg:' . $editFlg);

            }else{
                Log::error('プロフィールデータなし。'); 
            }
            $successFlg = true;

        } 
        catch(Exception $e) {
            Log::error('プロフィール編集画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('プロフィール編集画面の表示処理終了。');

        return response()->json([
            'profile' => $profile,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    }    
    
    // プロフィール更新処理
    public function update(EditProfileRequest $request)
    {
        // 最後にreturnするデータを定義
        $newProfile = null;
        $editFlg = false;
        $successFlg = false;

        try {
            Log::info('プロフィール更新処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);
                
                if($user->profile){
                    $profile = $user->profile;
                    $idPhotoPath = $user->profile->id_photo;
                    $editFlg = true;
                    Log::info('プロフィール更新');
                }else{
                    $profile = new Profile();
                    $idPhotoPath = null;
                    Log::info('プロフィール新規登録');
                }
                Log::info('DBの画像パス：' . $idPhotoPath);

                // 画像が選択された場合
                if($request->hasFile('id_photo')) {
                    Log::info('画像が選択されました。');

                    // 古い画像を削除(初回は除く)
                    if ($idPhotoPath !== null && $idPhotoPath !== 'user_icon.png') {
                        Log::info('古い画像があるので削除します。');
            
                        // 古い画像を削除して確認(delete,existはディレクトリも指定する)
                        Storage::disk('s3')->delete('/job_change_20/' . $idPhotoPath);
                        $deleteExists = !(Storage::disk('s3')->exists('job_change_20/' . $idPhotoPath));
            
                        if($deleteExists){
                            Log::info('古い画像削除結果：' . $deleteExists);
                        }else{
                            Log::info('古い画像削除失敗。');
                            Log::info('プロフィール更新処理終了。');

                            return [
                                'newProfile' => $newProfile,
                                'editFlg' => $editFlg,
                                'successFlg' => $successFlg,
                            ];
                        }
                    }else{
                        Log::info('古い画像はありません。');
                    }
            
                    // テストフラグの判定
                    $testFlg = $request->testFlg;
                    Log::info('testFlg：' . $testFlg);

                    // テストの場合はファイル名に作成日時を入れない
                    if(!empty($testFlg)){
                        Log::info('テストなのでファイル名に作成日時を入れません。');
                        $filename = 'avatar.jpg';
                    }else{
                        $filename = 'user_' . $user->id . '_' .  date('YmdHis') . '.jpg';
                    }
                    Log::info('ファイル名：' . $filename);
            
                    // 圧縮した画像を一時フォルダに保存
                    $tmpPath = storage_path('app/tmp/') . $filename;
                    Log::info('一時フォルダのパス：' . $tmpPath);
            
                    // InterventionImageのインスタンス作成
                    $image = InterventionImage::make($request->id_photo) // 本番は削除
                        // 自動回転しつつEXIF情報を削除
                        ->orientate()
                        // 最適なサイズへ変更
                        ->fit(300, 400, function ($constraint) {
                            $constraint->upsize(); // 大きくなるのを防止
                        })
                        ->encode('jpg') // 本番は削除
                        // save()がないとInterventionImageの処理が反映されない
                        ->save($tmpPath);
            
                    // 一時ファイルを保存してチェック
                    $putTmpFlg = Storage::disk('local')->exists('tmp/' . $filename);
                    Log::info('一時ファイル保存結果：' . $putTmpFlg);
            
                    // s3に保存してチェック
                    $file = new UploadedFile($tmpPath, $filename);
                    $file->storeAs('/job_change_20', $filename, 's3');
                    $storeFlg = Storage::disk('s3')->exists('job_change_20/' . $filename);
                    Log::info('s3画像保存結果：' . $storeFlg);
            
                    // 一時ファイルを削除してチェック
                    Storage::disk('local')->delete('/tmp/' . $filename);
                    $deleteTmpFlg = !(Storage::disk('local')->exists('tmp/' . $filename));
                    Log::info('一時ファイル削除結果：' . $deleteTmpFlg);
            
                    if ($storeFlg && $deleteTmpFlg) {
                        $profile->id_photo = $filename;
                        Log::info('s3画像保存、一時ファイル削除成功。');
                        
                    }else{
                        Log::info('s3画像保存、一時ファイル削除失敗。');
                        Log::info('プロフィール更新処理終了。');
    
                        return response()->json([
                            'newProfile' => $newProfile,
                            'editFlg' => $editFlg,
                            'successFlg' => $successFlg,
                        ]);            
                    }
                }
            
                // プロフィールテーブルのレコードに外部キーも設定する
                $newProfile = $user->profile()->save($profile->fill($request->except('id_photo')));
                Log::info('$newProfile' . $newProfile);

                if(!empty($newProfile)){
                    Log::info('プロフィール更新処理成功。');
                    $successFlg = true;

                }else{
                    Log::error('プロフィール更新処理失敗。'); 
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('プロフィール更新処理失敗。error_message = ' . $e);
        }

        Log::info('プロフィール更新処理終了。');

        return response()->json([
            'newProfile' => $newProfile,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    }    
}
