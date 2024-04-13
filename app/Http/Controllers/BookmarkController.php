<?php

namespace App\Http\Controllers;

use App\BookmarkJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class BookmarkController extends Controller
{    
    // 気になる確認
    public function check($id) {

        // 最後にreturnするデータを定義
        $bookmarkJobs = 0;
        $successFlg = false;

        try {
            Log::info('気になる求人の確認処理開始。対象アクション：' . __METHOD__);
            Log::info('気になる求人ID：' . $id);

            $bookmarkJobs = Auth::user()->bookmarkJobs()->where('job_id', $id)->count();

            if($bookmarkJobs === 1){
                Log::info('気になる求人です。');
            }else{
                Log::info('気になる求人ではありません。');               
            }
            $successFlg = true;

        } 
        catch(Exception $e) {
            $bookmarkJobs = null;
            Log::error('気になる求人の確認処理失敗。error_message = ' . $e);
        }

        Log::info('気になる求人の確認処理終了。');

        return response()->json([
            'bookmarkJobs' => $bookmarkJobs,
            'successFlg' => $successFlg,
        ]);
    }

    // 気になる登録
    public function store($id) {

        // 最後にreturnするデータを定義
        $successFlg = false;

        try {
            Log::info('気になる求人の登録処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();
            $user->storeBookmarkJobs()->attach($id);

            // 気になる登録確認(戻り値は true, false)
            $bookmarkStore = $user->isBookmark($id); 
            // $bookmarkStore = BookmarkJob::where('user_id', $user->id)->where('job_id', $id)->count();

            if($bookmarkStore){
                Log::info('気になる求人の登録成功。求人ID:' . $id);
                $successFlg = true;

            }else{
                Log::error('気になる求人の登録失敗。');
            }
        } 
        catch(Exception $e) {
            Log::error('気になる求人の登録処理失敗。error_message = ' . $e);
        }

        Log::info('気になる求人の登録処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
        ]);
    }

    // 気になる削除
    public function destroy($id) {

        // 最後にreturnするデータを定義
        $successFlg = false;

        try {
            Log::info('気になる削除処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();
            $user->storeBookmarkJobs()->detach($id);

            // 気になる削除確認(戻り値は true, false)
            $bookmarkDestroy = !($user->isBookmark($id));  
            // $bookmarkDestroy = BookmarkJob::where('user_id', $user->id)->where('job_id', $id)->count();

            if($bookmarkDestroy){
                Log::info('気になる削除成功。求人ID:' . $id);
                $successFlg = true;

            }else{
                Log::error('気になる削除失敗。');
            }
        } 
        catch(Exception $e) {
            Log::error('気になる削除失敗。error_message = ' . $e);
        }

        Log::info('気になる削除処理終了。');

        return response()->json([
            'successFlg' => $successFlg,
        ]);
    }    
}
