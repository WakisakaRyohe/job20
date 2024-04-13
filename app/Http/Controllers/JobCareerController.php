<?php
namespace App\Http\Controllers;

use App\JobCareer;
use App\User;
use App\Http\Requests\EditJobCareerRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobCareerController extends Controller
{
    // 職務経歴編集画面の表示
    public function edit(Request $request, $id)
    {
        // 最後にreturnするデータを定義
        $job_career = null;
        $editFlg = false;
        $forbiddenFlg = false;
        $successFlg = false;

        try {
            Log::info('職務経歴編集画面の表示処理開始。対象アクション：' . __METHOD__);

            if($id === 'undefined'){
                Log::info('登録画面');
                $successFlg = true;

            }else{
                Log::info('更新画面');
                $job_career = Auth::user()->job_careers()->where('id', $id)->first();
                    
                // 職務経歴データ取得の判定
                if(!is_null($job_career)){
                    $editFlg = true;
                    $successFlg = true;
                    Log::info('職務経歴ID：' . $job_career->id . '、$editFlg:' . $editFlg);

                }else{
                    $forbiddenFlg = true;
                    Log::error('職務経歴データ取得失敗。'); 
                }
            }    
        } 
        catch(Exception $e) {
            Log::error('職務経歴編集画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('職務経歴編集画面の表示処理終了。');

        return response()->json([
            'job_career' => $job_career,
            'editFlg' => $editFlg,
            'forbiddenFlg' => $forbiddenFlg,
            'successFlg' => $successFlg,
        ]);
    }    
    
    // 職務経歴の更新処理
    public function update(EditJobCareerRequest $request, $id)
    {
        // 最後にreturnするデータを定義
        $job_career = null;
        $editFlg = false;
        $successFlg = false;

        try {
            Log::info('職務経歴の登録/更新処理開始。対象アクション：' . __METHOD__);
            Log::info('$id：' . $id);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);
                    
                if($id){
                    $job_career = $user->job_careers()->where('id', $id)->first();
                    $editFlg = true;
                    Log::info('職務経歴の更新');
                }else{
                    $job_career = new JobCareer();
                    Log::info('職務経歴の新規登録');
                }

                // 職務経歴テーブルのレコードに外部キーも設定する
                $job_career = $user->job_careers()->save($job_career->fill($request->all()));
                Log::info('職務経歴ID:' . $job_career->id);

                if(!empty($job_career)){
                    Log::info('職務経歴の登録/更新処理成功。');
                    $successFlg = true;

                }else{
                    Log::error('職務経歴の登録/更新処理失敗。'); 
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('職務経歴の登録/更新処理失敗。error_message = ' . $e);
        }

        Log::info('職務経歴の登録/更新処理終了。');

        return response()->json([
            'job_career' => $job_career,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    }    

    // 職務経歴の削除処理
    public function delete($id)
    {        
        // 最後にreturnするデータを定義
        $deleteFlg = 1;
        $successFlg =false;

        try {
            Log::info('職務経歴の削除処理開始。対象アクション：' . __METHOD__);

            $job_career = JobCareer::find($id);

            // 職務経歴データ取得の判定
            if($job_career){
                Log::info('職務経歴ID：' . $job_career->id);
                
                // 職務経歴の削除
                $job_career->delete();

                // 職務経歴の削除結果判定フラグ
                $deleteFlg = JobCareer::where('id', $id)->count();
                // $deleteFlg = !(Auth::user()->checkJobCareer($id));
                
                if($deleteFlg === 0){
                    Log::info('職務経歴の削除成功。');
                    $successFlg =true;

                }else{
                    Log::error('職務経歴の削除失敗。');   
                }            
            }else{
                Log::error('職務経歴データ取得失敗。'); 
            }
        }
        catch(Exception $e) {
            Log::error('職務経歴の削除処理失敗。error_message = ' . $e);
        }

        Log::info('職務経歴の削除処理終了。');

        return response()->json([
            'deleteFlg' => $deleteFlg,
            'successFlg' => $successFlg,
        ]);
    }    
}
