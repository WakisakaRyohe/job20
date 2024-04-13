<?php

namespace Tests\Unit;

use App\Bookmark;
use App\BoughtJob;
use App\Job;
use App\SoldJob;
use App\User;
use App\Mail\Withdrawal;
use App\Traits\ModelDeleteTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WithdrawalTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストデータ作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->job = factory(Job::class)->create();
    }

    // 退会画面の表示
    public function testWithdrawalShow()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 退会画面の非同期通信
        $response = $this->get('/job20/web/withdrawal')
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'hasBords' => false,
            'successFlg' => true,
        ]);

        // 退会画面の表示確認
        $response = $this->get('/withdrawal')->assertStatus(200);     
        
        // ユーザーと求人削除
        $this->userModelDelete();
        $this->jobModelDelete();
    }

    // 退会処理成功
    public function testWithdrawalSuccess()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);
        
        // 実際にはメールを送らないように設定
        Mail::fake();

        // 画像データ作成
        $file = UploadedFile::fake()->image('avatar.jpg', 3800, 4800)->size(6800);

        // アイコン画像アップロード

        // 編集するデータ
        $postData = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'file' => $file,
            'testFlg' => true,
        ];

        // プロフィール編集
        $response = $this->post('/job20/web/profile', $postData)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // 画像がストレージに保存されていることを確認
        Storage::disk('s3')->assertExists('job_change_20/' . $file->name);

        // 気になる登録
        $response = $this->post('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkStore' => 1,
        ]);
          
        // 求人応募処理（ここで気になるも削除）
        $response = $this->post('/job20/web/job/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'storeFlg' => true,
        ]);

        // 退会処理
        $response = $this->delete('/job20/web/withdrawal')
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);
        
        // 画像削除後に存在しないことを確認
        Storage::disk('s3')->assertMissing('job_change_20/' . $file->name);

        // メールが1回送信されたことをアサート
        Mail::assertSent(Withdrawal::class, 1);

        // 全テーブルの削除確認
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
        $this->assertDatabaseMissing('bords', [
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseMissing('applied_jobs', [
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseMissing('bookmark_jobs', [
            'user_id' => $this->user->id,
        ]);

        // ユーザーと求人削除
        $this->jobModelDelete();
    }
}
