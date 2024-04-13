<?php

namespace Tests\Unit;

use App\User;
use App\Job;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SearchTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストユーザ作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }
    
    // 求人検索ページ表示
    public function testIndex()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // ページネーションのパラメータ
        $job_range = 10;
        $total = Job::all()->count();
        $jobsNum =  $job_range;
        $last_page = ceil($total / $job_range);
                
        // 検索パラメータがない場合の表示確認
        $response = $this->get('/search')->assertStatus(200);
    
        // 検索パラメータがない場合のデータ取得
        $response = $this->post('/job20/web/index', [
			'current_page' => 1,
			'job_range' => $job_range,
		])
        ->assertStatus(200)
        ->assertJsonCount(11)
        ->assertJsonFragment([
            'last_page' => $last_page,
            'total' => $total,
            'jobsNum' => $jobsNum,
            'successFlg' => true,
        ]);
    }

    // 求人検索（業種のみ選択）
    public function testSearch()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);
        
        // 検索パラメータ
        $industry = rand(1, 12);
        $occupation = rand(1, 14);
        $employment_status = rand(1, 6);
        $prefecture = rand(1, 47);
        $annual_income = rand(1, 5);
        $order = rand(1, 1);
        $keyword = null;

        // 画面表示確認
        $response = $this->get('/search?industry=' . $industry . '&occupation=' . $occupation . '&employment_status=' . $employment_status . 
        '&prefecture=' . $prefecture . '&annual_income=' . $annual_income . '&order=' . $order . '&keyword=' . $keyword)->assertStatus(200);

        // 検索パラメータを元にデータ取得
        $response = $this->post('/job20/web/search', [
            'industry' => $industry,
            'occupation' => $occupation,
            'employment_status' => $employment_status,
            'prefectures' => $prefecture,
            'annual_incomes' => $annual_income,
            'orders' => $order,
            'keyword' => $keyword,
            'current_page' => 1,
            'job_range' => 10,
        ])
        ->assertStatus(200)
        ->assertJsonCount(11)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // ユーザー削除
        $this->userModelDelete();        
    }
}
