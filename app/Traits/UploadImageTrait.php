<?php
namespace App\Traits;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use Imagick;

trait UploadImageTrait
{
    public static function upload(Request $request, $testFlg)
    {
        // $testFlgで画像アップロードテストか判定

        $user = Auth::user();
        $idPhotoPath = $user->profile->id_photo;
        Log::info('$idPhotoPath：' . $idPhotoPath);

        // 古い画像を削除(初回は除く)
        if ($idPhotoPath !== null) {
            Log::info('古い画像があるので削除します。');

            // 古い画像を削除して確認(delete,existはディレクトリも指定する)
            Storage::disk('s3')->delete('/job_change_20/' . $idPhotoPath);
            $deleteExists = !(Storage::disk('s3')->exists('job_change_20/' . $idPhotoPath));

            if($deleteExists){
                Log::info('古い画像削除結果：' . $deleteExists);
            }else{
                Log::info('古い画像削除失敗。');
                $errorFlg = true;

                return [
                    'path' => null,
                    'errorFlg' => true,
                ];
            }
        }

        // テストの場合はファイル名に作成日時を入れない
        if($testFlg === true){
            Log::info('テストなのでファイル名に作成日時を入れません。');
            $filename = 'avatar.jpg';
        }else{
            $filename = date('YmdHis') . '.jpg';
        }
        Log::info('ファイル名：' . $filename);

        // 圧縮した画像を一時フォルダに保存
        $tmpPath = storage_path('app/tmp/') . $filename;
        Log::info('一時フォルダのパス：' . $tmpPath);

        // Imagickのインスタンス作成 : 本番で使う
        // $imagick = new \Imagick();
        // $imagick->readImage($request->file->getPathname());
        // // jpgフォーマットに変換
        // $imagick->setImageFormat('jpg');

        // InterventionImageのインスタンス作成
        // $image = \InterventionImage::make($imagick)
        $image = InterventionImage::make($request->id_photo) // 本番は削除
        // 自動回転しつつEXIF情報を削除
        ->orientate()
        // 最適なサイズへ変更
        ->fit(400, 300, function ($constraint) {
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
            Log::info('s3画像保存、一時ファイル削除成功。');

            // 画像パスを返却
            return [
                'path' => $filename,
                'errorFlg' => false,
            ];

        }else{
            Log::info('s3画像保存、一時ファイル削除失敗。');

            return [
                'path' => null,
                'errorFlg' => true,
            ];
        }
    }
}