<?php

namespace App\Http\Controllers;

use App\Traits\WebResumeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class BookmarkJobsController extends Controller
{
    use WebResumeTrait;

    public function __invoke(Request $request)
    {
        Log::info( '気になる求人一覧のデータ取得処理開始。対象アクション：' . __METHOD__);

        // 最後にreturnするデータを定義
        $jobs = null;
        $user = null;
        $hasWebResume = false;
        $successFlg= false;

        try {
            // WEB履歴書情報を取得
            $data = $this->getWebResume();
            $user = $data['user'];
            $hasWebResume = $data['hasWebResume'];
            
            if(!empty($user)){
                Log::info('ユーザーデータ取得成功。');

                // 気になる求人を取得し件数カウント
                $jobsData = $user->bookmarkJobs();
                $jobsDataNum = $jobsData->count();
                Log::info('気になる求人数：' . $jobsDataNum);                        

                // 非同期通信ではリレーション先データのプロパティは参照できないため、テーブル結合で業種名を取得
                $jobs = $jobsData->select('jobs.id', 'jobs.job_name', 'jobs.photo1', 'jobs.job_catch', 'jobs.job_summary', 'jobs.work_location', 'jobs.annual_income', 'jobs.deadline', 'jobs.created_at', 'jobs.updated_at', 
                    'companies.company_name as company_name', 'industries.industry_name as industry_name', 'occupations.occupation_name as occupation_name', 
                    'employment_statuses.employment_status_name as employment_status_name', 'prefectures.prefecture_name as prefecture_name')
                    ->join('companies', 'jobs.company_id', '=', 'companies.id')
                    ->join('industries', 'jobs.industry_id', '=', 'industries.id')
                    ->join('occupations', 'jobs.occupation_id', '=', 'occupations.id')
                    ->join('employment_statuses', 'jobs.employment_status_id', '=', 'employment_statuses.id')
                    ->join('prefectures', 'jobs.prefecture_id', '=', 'prefectures.id')
                    ->orderBy('jobs.deadline', 'asc')->get();  

                if($jobs !== null){
                    Log::info('気になる求人の取得成功。');    
                    $successFlg = true;
                    
                }else{
                    Log::error('気になる求人の取得失敗。');
                }
            }else{
                Log::error('ユーザーデータ取得失敗。');
            }
        } 
        catch(Exception $e) {
            Log::error('気になる求人一覧のデータ取得処理失敗。error_message = ' . $e);
        }

        Log::info('気になる求人一覧のデータ取得処理終了。' );

        return response()->json([
            'user' => $user,
            'jobs' => $jobs,
            'hasWebResume' => $hasWebResume,
            'successFlg' => $successFlg,
        ]);
    }
}
