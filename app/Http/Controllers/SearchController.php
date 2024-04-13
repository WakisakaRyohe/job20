<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Job;
use App\Company;
use App\Traits\SearchJobsTrait;
use Exception;

class SearchController extends Controller
{
    use SearchJobsTrait;

    // 求人検索画面の表示
    public function index(Request $request)
    {
        // 最後にreturnするデータを定義
        $jobs = null;
        $pickupJobs = null;
        $last_page = 0;
        $total = 0;
        $jobsNum = 0;
        $applyIDs = [];
        $successFlg= false;

        try {
            Log::info('求人検索画面の表示処理開始。対象アクション：' . __METHOD__);

            Log::info('ページ数：' . $request->current_page);

            // 求人総数
            $total = Job::all()->count();
            // 最終ページ ($request->job_range = 10)
            $last_page = ceil($total / $request->job_range);

            if($total >= 1 && $last_page >= 1){
                Log::info('求人総数：' . $total . '、最終ページ：' . $last_page);

                // 求人一覧と応募済み求人のIDを取得
                $data = $this->selectJobs();
                $query = $data['query'];
                $applyIDs = $data['applyIDs'];

                // 求人を10件ずつ取得
                $jobs = $query->orderBy('jobs.created_at', 'desc')
                    ->offset(($request->current_page - 1) * $request->job_range)
                    ->limit($request->job_range)->get();
                          
                // カルーセル用の求人
                $pickupJobs = $query->where('jobs.pickupFlg', '=', '1')
                    ->orderBy('jobs.created_at', 'desc')->offset(0)
                    ->limit(5)->get();

                // 配列化したこだわり条件の文字列をセット
                $this->getCommitments($jobs);
                $this->getCommitments($pickupJobs);
    
                // １ページに表示する求人数
                $jobsNum = $jobs->count();
                // カルーセル用の求人数
                $pickupJobsNum = $pickupJobs->count();

                if($jobsNum >= 1 && $pickupJobsNum === 5){
                    Log::info('１ページに表示する求人数：' . $jobsNum);
                    Log::info('カルーセル用の求人数：' . $pickupJobsNum);
    
                    $successFlg = true;
                    Log::info('求人検索画面の表示処理終了。');

                }else{
                    Log::error('検索にヒットした求人が０件です。１ページに表示する求人数の取得失敗。'); 
                    $successFlg = true;
                }  
            }else{
                Log::error('求人総数と最終ページ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('求人検索画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('求人検索画面の表示処理終了。');

        return response()->json([
            'jobs' => $jobs,
            'pickupJobs' => $pickupJobs,
            'last_page' => $last_page,
            'total' => $total,
            'jobsNum' => $jobsNum,
            'applyIDs' => $applyIDs,
            'successFlg' => $successFlg,
        ]);
    }

    // 求人の検索処理
    public function search(Request $request)
    {
        // 最後にreturnするデータを定義
        $jobs = null;
        $pickupJobs = null;
        $last_page = 0;
        $total = 0;
        $jobsNum = 0;
        $applyIDs = [];
        $successFlg= false;

        try {
            Log::info('求人検索処理開始。対象アクション：' . __METHOD__);

            // 求人一覧と応募済み求人のIDを取得
            $data = $this->selectJobs();
            $query = $data['query'];
            $applyIDs = $data['applyIDs'];
            
            // カルーセル用クエリ
            $pickupQuery = $this->selectCarouselJobs();

            //検索時に入力した項目を取得
            $industry = $request->industry;
            $occupation = $request->occupation;
            $employment_status = $request->employment_status;
            $prefecture = $request->prefecture;
            $annual_income = $request->annual_income;
            $keyword = $request->keyword;
            $commitments = $request->commitments;
            $sort = $request->sort;
                        
            // 業種
            if ($industry != 0) {
                $query->where('industry_id', $industry);
                Log::info('選択した業種ID：' . $industry);
            }

            // 職種
            if ($occupation != 0) {
                $query->where('occupation_id', $occupation);
                Log::info('選択した職種ID：' . $occupation);
            }

            // 雇用形態
            if ($employment_status != 0) {
                $query->where('employment_status_id', $employment_status);
                Log::info('選択した雇用形態ID：' . $employment_status);
            }
            
            // 都道府県
            if ($prefecture != 0) {
                $query->where('prefecture_id', $prefecture);
                Log::info('選択した都道府県ID：' . $prefecture);
            }

            // 年収
            if ($annual_income != 0) {
                switch($annual_income){
                    case 1:
                        $query->where('annual_income', '>=', 2000000);
                        break;
                    case 2:
                        $query->where('annual_income', '>=', 2500000);
                        break;
                    case 3:
                        $query->where('annual_income', '>=', 3000000);
                        break;
                    case 4:
                        $query->where('annual_income', '>=', 3500000);
                        break;
                    case 5:
                        $query->where('annual_income', '>=', 4000000);
                        break;
                    case 6:
                        $query->where('annual_income', '>=', 4500000);
                        break;
                    case 7:
                        $query->where('annual_income', '>=', 5000000);
                        break;
                    case 8:
                        $query->where('annual_income', '>=', 5500000);
                        break;
                    case 9:
                        $query->where('annual_income', '>=', 6000000);
                        break;
                    case 10:
                        $query->where('annual_income', '>=', 6500000);
                        break;
                    case 11:
                        $query->where('annual_income', '>=', 7000000);
                        break;
                    case 12:
                        $query->where('annual_income', '>=', 7500000);
                        break;
                    case 13:
                        $query->where('annual_income', '>=', 8000000);
                        break;
                    case 14:
                        $query->where('annual_income', '>=', 8500000);
                        break;
                    case 15:
                        $query->where('annual_income', '>=', 9000000);
                        break;
                    case 16:
                        $query->where('annual_income', '>=', 9500000);
                        break;
                    case 17:
                        $query->where('annual_income', '>=', 10000000);
                        break;
                }
                Log::info('選択した年収ID：' . $annual_income);
            }
    
            // こだわり条件
            if (count($commitments) > 0) {
                Log::info('検索したこだわり条件数：' . count($commitments));

                // 単語をループで回し、対象カラムと部分一致するものがあれば、$queryとして保持される
                foreach($commitments as $value) {
                    $query->where('commitments', 'LIKE', '%'.$value.'%');
                    Log::info('検索したこだわり条件：' . $value);
                };
            }
            
            //  キーワード
            if ($keyword !== 'null') {

                // 全角スペースを半角に変換
                $spaceConversion = mb_convert_kana($keyword, 's');

                // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
                $wordArraySearched = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);

                // 単語をループで回し、対象カラムと部分一致するものがあれば、$queryとして保持される
                foreach($wordArraySearched as $value) {
                    // A and (B or C)
                    $query->where(function($query) use($value){
                        $query->orWhere('job_name', 'LIKE', '%'.$value.'%')
                            ->orWhere('job_summary', 'LIKE', '%'.$value.'%')
                            ->orWhere('job_description', 'LIKE', '%'.$value.'%')
                            ->orWhere('application_conditions', 'LIKE', '%'.$value.'%')
                            ->orWhere('work_location', 'LIKE', '%'.$value.'%')
                            ->orWhere('working_hours', 'LIKE', '%'.$value.'%')
                            ->orWhere('salary', 'LIKE', '%'.$value.'%')
                            ->orWhere('holiday', 'LIKE', '%'.$value.'%')
                            ->orWhere('welfare', 'LIKE', '%'.$value.'%')
                            ->orWhere('selection', 'LIKE', '%'.$value.'%')
                            ->orWhere('company_name', 'LIKE', '%'.$value.'%');
                    });
                    Log::info('入力したキーワード：' . $value);
                }
            }

            // 並び順
            if ($sort > 0) {
                switch($sort){
                    case 1:
                        // 新着順
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 2:
                        // 締切が近い順
                        $query->orderBy('deadline', 'asc');
                        break;
                    case 3:
                        // 年収が高い順
                        $query->orderBy('annual_income', 'desc');
                        break;
                    }
                Log::info('選択した並び順ID：' . $sort);
            }
                        
            // 検索にヒットした求人数
            $total = $query->get()->count();
            // 最終ページ ($request->job_range = 10)
            $last_page = ceil($total / $request->job_range);

            if($total >= 0 && $last_page >= 0){
                Log::info('検索にヒットした求人数：' . $total . '、最終ページ：' . $last_page);

                // 求人を10件ずつ取得      
                $jobs = $query->offset(($request->current_page - 1) * $request->job_range)
                    ->limit($request->job_range)->get();

                // カルーセル用の求人
                $pickupJobs = $pickupQuery->where('jobs.pickupFlg', '=', '1')
                    ->orderBy('jobs.created_at', 'desc')
                    ->limit(5)->get();

                // 配列化したこだわり条件の文字列をセット
                $this->getCommitments($jobs);

                // １ページに表示する求人数
                $jobsNum = $jobs->count();
                Log::info('１ページに表示する求人数：' . $jobsNum);

                // カルーセル用の求人数
                $pickupJobsNum = $pickupJobs->count();
                Log::info('カルーセル用の求人数：' . $pickupJobsNum);

                if($pickupJobsNum === 5){    
                    $successFlg = true;
                    Log::info('求人検索処理終了。');

                }else{
                    Log::error('カルーセル用の求人取得失敗。'); 
                }
            }else{
                Log::error('求人総数と最終ページ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('求人検索処理失敗。error_message = ' . $e);
        }

        Log::info('求人検索処理終了。');

        return response()->json([
            'jobs' => $jobs,
            'pickupJobs' => $pickupJobs,
            'last_page' => $last_page,
            'total' => $total,
            'jobsNum' => $jobsNum,
            'applyIDs' => $applyIDs,
            'successFlg' => $successFlg,
        ]);
    }
}
