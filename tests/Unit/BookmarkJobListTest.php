<?php

namespace Tests\Unit;

use App\Job;
use App\User;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookmarkJobListTest extends TestCase
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
    
    public function testNoBookmarkJobs()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 気になる登録前は、気になる求人一覧が表示されない
        $response = $this->post('/job20/web/list',[
            'appliedFlg' => false, 
        ])
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => false,
        ]);
    }

    public function testHasBookmarkJobs()
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
        
        // 気になる登録後は、気になる求人一覧が表示される
        $response = $this->post('/job20/web/list',[
            'appliedFlg' => false, 
        ])
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);
        
        // 気になる一覧ページの表示確認
        $response = $this->get('/bookmark_jobs')->assertStatus(200);
    }
}
