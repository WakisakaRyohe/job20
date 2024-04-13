<?php

namespace App\Http\Controllers;

use App\Job;
use App\Traits\SearchJobsTrait;
use Illuminate\Support\Facades\Log;
use Exception;
        
class ToppageController extends Controller
{
    use SearchJobsTrait;

    public function __invoke()
    {
        // 最後にreturnするデータを定義
        $jobs = null;
        $successFlg= false;

        try {
            Log::info('トップページ表示処理開始。対象アクション：' . __METHOD__);

            // カルーセル用クエリ
            $query = $this->selectCarouselJobs();
            // カルーセル用の求人
            $jobs = $query->orderBy('jobs.created_at', 'desc')->limit(5)->get();
        
            $jobsCountNum = $jobs->count();
            Log::info('求人数：' . $jobsCountNum);

            if($jobsCountNum === 5){
                $successFlg = true;
                Log::info('求人データ取得成功。求人数：' . $jobsCountNum);
            }else{
                Log::error('求人データ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('トップページ表示処理失敗。error_message = ' . $e);
        }

        Log::info('トップページ表示処理終了。');

        return response()->json([
            'jobs' => $jobs,
            'successFlg' => $successFlg,
        ]);
    }
}
