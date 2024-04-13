<?php

namespace Tests\Unit;

use App\Job;
use App\User;
use App\Bookmark;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookmarkJobTest extends TestCase
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
    
    public function testBookmarkCheck()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 気になる確認
        $response = $this->get('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkJobs' => 0,
        ]);
    }

    public function testBookmarkStore()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 気になる登録
        $response = $this->post('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkStore' => 1,
        ]);

        // 気になる登録確認
        $response = $this->get('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkJobs' => 1,
        ]);
        
        // bookmark_jobsテーブルの登録確認
        $this->assertDatabaseHas('bookmark_jobs', [
            'user_id' => $this->user->id,
            'job_id' => $this->job->id,
        ]);
    }

    public function testBookmarkDestroy()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 気になる登録
        $response = $this->post('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkStore' => 1,
        ]);

        // 気になる登録確認
        $response = $this->get('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkJobs' => 1,
        ]);
        
        // bookmark_jobsテーブルの登録確認
        $this->assertDatabaseHas('bookmark_jobs', [
            'user_id' => $this->user->id,
            'job_id' => $this->job->id,
        ]);
        
        // 気になる削除
        $response = $this->post('/job20/web/bookmark/' . $this->job->id . '/delete')
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkDestroy' => 0,
        ]);
        
        // 気になる削除確認
        $response = $this->get('/job20/web/bookmark/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'bookmarkJobs' => 0,
        ]);
        
        // bookmark_jobsテーブルの削除確認
        $this->assertDatabaseMissing('bookmark_jobs', [
            'user_id' => $this->user->id,
            'job_id' => $this->job->id,
        ]);
    }
}
