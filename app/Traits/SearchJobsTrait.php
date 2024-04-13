<?php
namespace App\Traits;

use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait SearchJobsTrait
{
    public static function selectJobs()
    {
        // 最後にreturnするデータを定義
        $data = [];

        try {
            Log::info('求人検索クエリ作成処理開始。対象アクション：' . __METHOD__);

            // 非同期通信ではリレーション先データのプロパティは参照できないため、テーブル結合で取得
            $query = Job::select('jobs.id', 'jobs.photo1', 'jobs.job_name', 'jobs.job_catch', 
                'jobs.job_summary', 'jobs.job_description', 'jobs.application_conditions', 
                'jobs.work_location', 'jobs.annual_income', 'jobs.salary', 'jobs.commitments', 'jobs.pickupFlg', 'jobs.deadline', 
                'jobs.created_at', 'jobs.updated_at', 'companies.company_name as company_name', 'companies.company_icon_image as company_img', 
                'industries.industry_name as industry_name', 'occupations.occupation_name as occupation_name', 
                'employment_statuses.employment_status_name as employment_status_name', 'prefectures.prefecture_name as prefecture_name')
                ->join('companies', 'jobs.company_id', '=', 'companies.id')
                ->join('industries', 'jobs.industry_id', '=', 'industries.id')
                ->join('occupations', 'jobs.occupation_id', '=', 'occupations.id')
                ->join('employment_statuses', 'jobs.employment_status_id', '=', 'employment_statuses.id')
                ->join('prefectures', 'jobs.prefecture_id', '=', 'prefectures.id');
            
            // 応募済み求人のidを配列化
            $applyIDs = Auth::user()->appliedJobs()->pluck('job_id')->toArray();

            // 配列にデータを挿入
            $data['query'] = $query;
            $data['applyIDs'] = $applyIDs;          
        } 
        catch(Exception $e) {
            Log::error('求人検索クエリ作成処理失敗。error_message = ' . $e);
        }
        Log::info('求人検索クエリ作成処理終了。');

        return $data;
    }

    public static function selectCarouselJobs()
    {
        // 最後にreturnするデータを定義
        $query = '';

        try {
            Log::info('カルーセル用クエリ作成処理開始。対象アクション：' . __METHOD__);
        
            // 非同期通信ではリレーション先データのプロパティは参照できないため、テーブル結合で取得
            $query = Job::select('jobs.id', 'jobs.photo1', 'jobs.job_name', 'jobs.pickupFlg', 'jobs.created_at', 
                'companies.company_name as company_name', 'companies.company_icon_image as company_img', 'occupations.occupation_name as occupation_name')
                ->join('companies', 'jobs.company_id', '=', 'companies.id')
                ->join('occupations', 'jobs.occupation_id', '=', 'occupations.id');
        
        } 
        catch(Exception $e) {
            Log::error('カルーセル用クエリ作成処理失敗。error_message = ' . $e);
        }

        Log::info('カルーセル用クエリ作成処理終了。');

        return $query;
    }

    public static function getCommitments($jobs)
    {
        $jobs->map(function($job) {
            // こだわり条件がある場合
            if(!empty($job->commitments)){
                $newArray = [];

                // こだわり条件が１つの場合(こだわり条件の文字列に「,」が含まれていない)
                if( ! str_contains($job->commitments, ',') ) {
                    array_push($newArray, $job->commitments);

                // こだわり条件が複数の場合
                }else{
                    // 複数のこだわり条件が「,」区切りの文字列でDBに保存されているので、配列に変換
                    $newArray = explode(",", $job->commitments);
                }

                Log::info('こだわり条件数：'. count($newArray));
                
                // 配列化したこだわり条件の文字列をセット
                $job->commitments = $newArray;
            }
        });
    }
}