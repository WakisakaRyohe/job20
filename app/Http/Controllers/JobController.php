<?php

namespace App\Http\Controllers;

use App\AppliedJob;
use App\Bord;
use App\Company;
use App\EmploymentStatus;
use App\Job;
use App\Message;
use App\Industry;
use App\Occupation;
use App\User;
use App\Mail\Apply;
use App\Traits\WebResumeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class JobController extends Controller
{
    use WebResumeTrait;

    // 求人詳細画面の表示
    public function show(Job $job, $id)
    {
        Log::info('求人詳細画面の表示処理開始。対象アクション：' . __METHOD__);

        // 最後にreturnするデータを定義
        $user = null;
        $job = null;
        $company = null;
        $isApplied = null;
        $hasWebResume = false;
        $successFlg = false;

        try {
            // WEB履歴書情報を取得
            $data = $this->getWebResume();
            $user = $data['user'];
            $hasWebResume = $data['hasWebResume'];
            
            $job = Job::select('jobs.id', 'jobs.job_name', 'jobs.photo1', 'jobs.photo2', 'jobs.photo3', 
                'jobs.job_catch', 'jobs.job_summary', 'jobs.job_description', 'jobs.application_conditions', 
                'jobs.work_location', 'jobs.working_hours', 'jobs.annual_income', 'jobs.salary', 
                'jobs.holiday', 'jobs.welfare', 'jobs.selection', 'jobs.deadline', 'jobs.commitments', 'jobs.created_at', 'jobs.updated_at', 
                'companies.id as company_id', 'industries.industry_name as industry_name', 'occupations.occupation_name as occupation_name', 
                'employment_statuses.employment_status_name as employment_status_name', 'prefectures.prefecture_name as prefecture_name')
                ->join('companies', 'jobs.company_id', '=', 'companies.id')
                ->join('industries', 'jobs.industry_id', '=', 'industries.id')
                ->join('occupations', 'jobs.occupation_id', '=', 'occupations.id')
                ->join('employment_statuses', 'jobs.employment_status_id', '=', 'employment_statuses.id')
                ->join('prefectures', 'jobs.prefecture_id', '=', 'prefectures.id')
                ->find($id);

            // ユーザーデータと求人データ取得の判定
            if(!is_null($user) && !is_null($job)){
                Log::info('ユーザーID: ' . $user->id . '、求人ID: ' . $job->id);

                $company = Company::select('companies.id', 'companies.company_name as company_name', 'industries.industry_name as company_industry_name',
                    'companies.address as company_address', 'companies.business_content as company_business_content')
                    ->join('industries', 'companies.companies_industry_id', '=', 'industries.id')
                    ->find($job->company_id);

                // 会社データ取得の判定
                if(!is_null($company)){
                    Log::info('会社ID: ' . $company->id);

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

                    }else{
                        Log::info('こだわり条件はありません。');
                    }

                    // 自分が応募した求人かの判定フラグ
                    $isApplied = $user->isApplied($id);

                    if(!is_null($isApplied)){
                        Log::info('応募判定フラグ：' . $isApplied);
                        $successFlg = true;

                    }else{
                        Log::error('応募判定フラグ取得失敗。'); 
                    }
                }else{
                    Log::error('会社データ取得失敗。'); 
                }
            }else{
                Log::error('ユーザーデータまたは求人データ取得失敗。'); 
            }
        }
        catch(Exception $e) {
            Log::error('求人詳細画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('求人詳細画面の表示処理終了。');

        return response()->json([
            'user' => $user,
            'job' => $job,
            'company' => $company,
            'isApplied' => $isApplied,
            'hasWebResume' => $hasWebResume,
            'successFlg' => $successFlg,
        ]);
    }    

    // 求人応募処理
    public function apply(Request $request, $id)
    {
        // 最後にreturnするデータを定義
        $bordId = null;
        $successFlg = false;

        try {
            Log::info('求人応募処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();
            $job = Job::with('company')->find($id);

            // ユーザーデータと求人データ取得の判定
            if(!is_null($user) && !is_null($job)){
                Log::info('ユーザーID: ' . $user->id . '、求人ID: ' . $job->id);
            
                // 応募済み求人テーブルに登録日時付きで登録して確認
                $user->storeAppliedJobs()->attach($id);
                $appliedFlg = $user->isApplied($id);

                if($appliedFlg){
                    Log::info('応募履歴テーブル登録成功。');

                    // 気になる登録確認(戻り値は true, false)
                    $isBookmark = $user->isBookmark($id);

                    // 気になる登録があれば削除
                    if($isBookmark){
                        Log::info('気になる登録を削除します。'); 
                        $user->bookmarkJobs()->detach($id);

                        // 気になる削除確認(戻り値は true, false)
                        $bookmarkDestroy = !($user->isBookmark($id));  

                        if($bookmarkDestroy){
                            Log::info('気になる削除成功。');
                        }else{
                            Log::error('気になる削除失敗。');
                        }

                    }else{
                        Log::error('気になる登録はありません。');
                    }

                    // 連絡掲示板作成
                    $bord = new Bord();
                    $bord->user_id = $user->id;
                    $bord->company_id = $job->company_id;
                    $bord->job_id = $id;
                    $bord->save();

                    // 会社の初回メッセージ作成
                    $message = new Message();
                    $message->bord_id = $bord->id;
                    $message->sender_id = $job->company_id;
                    $message->receiver_id = $user->id;
                    $message->user_flg = false;
                    $message->message = <<<EOT
                    {$job->company->company_name}採用担当でございます。
                    この度は弊社求人にご応募いただきありがとうございます。
                    
                    貴殿からのご応募を確かに受付いたしました。
                    今後の詳細については、最大1週間前後に電話またはメッセージにてご連絡いたします。
                    ご連絡まで今しばらくお待ちください。

                    ※このメールはご応募いただいた方への自動返信メールです。
                    返信は不要ですのでお気遣いないようお願いいたします。

                    {$job->company->company_name}
                    採用担当
                    EOT;
                    $message->save();
                    
                    if( !is_null($bord) && !is_null($message) ){
                        Log::info('連絡掲示板ID:' . $bord->id . '、メッセージID:' . $message->id); 

                        // メールで使う情報を変数に格納
                        $params = [
                            'userName' => $user->name,
                            'jobName' => $job->job_name,
                            'jobId' => $id,
                            'bordId' => $bord->id,
                        ];
    
                        // 応募者にメール送信
                        Mail::to($user->email)->send(new Apply($params));
                        // テスト送信
                        // Mail::to('kokuto160@gmail.com')->send(new Apply($params));

                        // メール送信結果
                        $sendMail = count(Mail::failures());
    
                        if ($sendMail == 0) {
                            Log::info('応募者にメール送信成功。');
                            $bordId = $bord->id;
                            $successFlg = true;

                        }else{
                            Log::error('応募者にメール送信失敗。');
                        }    
                    }else{
                        Log::error('連絡掲示板とメッセージ作成失敗。');
                    }
                }else{
                    Log::error('応募履歴テーブルに登録失敗。');
                }
            }else{
                Log::error('ユーザーデータと求人データ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('求人応募処理失敗。error_message = ' . $e);
        }

        Log::info('求人応募処理終了。');

        return response()->json([
            'bordId' => $bordId,
            'successFlg' => $successFlg,
        ]);
    }    
}
