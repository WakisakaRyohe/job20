<?php

namespace Tests\Unit;

use App\Job;
use App\User;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MessageListTest extends TestCase
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
    
    public function testNoMessage()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 求人応募前は、会社からのメッセージ一覧が表示されない
        $response = $this->get('/job20/web/messages')
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => false,
        ]);
    }

    public function testHasMessage()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 求人応募処理
        $response = $this->post('/job20/web/job/' . $this->job->id)
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'storeFlg' => true,
        ]);
                
        // 求人応募後は、会社からのメッセージ一覧が表示される
        $response = $this->get('/job20/web/messages')
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);
        
        // 会社からのメッセージ一覧の表示確認
        $response = $this->get('/messages')->assertStatus(200);
    }
}
