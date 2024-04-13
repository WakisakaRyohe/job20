<?php

namespace Tests\Unit;

use App\Job;
use App\User;
use App\Mail\Apply;
use App\Traits\ModelDeleteTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class JobShowTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストユーザと求人作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->job = factory(Job::class)->create();
    }

    public function tearDown(): void
    {
        // ユーザーと求人削除
        $this->userModelDelete();
        $this->jobModelDelete();        
    }
    
    public function testJobShow()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);
        
        // 求人詳細画面の非同期通信
        $response = $this->get('/job20/web/job/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(5)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // 求人詳細画面の表示確認
        $response = $this->get('/job/' . $this->job->id)->assertStatus(200);
    }

    public function testJobApply()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);
        
        // 実際にはメールを送らないように設定
        Mail::fake();

        // 気になる登録
        $response = $this->post('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkStore' => 1,
        ]);
                
        // 求人応募処理
        $response = $this->post('/job20/web/job/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'storeFlg' => true,
        ]);

        // applied_jobsテーブルの登録確認
        $this->assertDatabaseHas('applied_jobs', [
            'user_id' => $this->user->id,
            'job_id' => $this->job->id,
        ]);

        // bookmark_jobsテーブルの削除確認
        $this->assertDatabaseMissing('bookmark_jobs', [
            'user_id' => $this->user->id,
            'job_id' => $this->job->id,
        ]);

        // bordsテーブルの登録確認
        $this->assertDatabaseHas('bords', [
            'user_id' => $this->user->id,
            'company_id' => $this->job->company_id,
            'job_id' => $this->job->id,
        ]);

        // messagesテーブルの登録確認
        $this->assertDatabaseHas('messages', [
            'sender_id' => 'company_' . (string)$this->job->company->id,
            'receiver_id' => 'user_' . (string)$this->user->id,
        ]);
        
        // メールが1回送信されたことをアサート
        Mail::assertSent(Apply::class, 1);
    }
}
