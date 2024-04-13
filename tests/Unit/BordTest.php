<?php

namespace Tests\Unit;

use App\Bord;
use App\Job;
use App\Message;
use App\User;
use App\Mail\newMessage;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BordTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストユーザと求人作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->job = factory(Job::class)->create();
        $this->bord = factory(Bord::class)->create();
    }

    public function tearDown(): void
    {
        // ユーザーと求人削除
        $this->userModelDelete();
        $this->jobModelDelete();
    }

    // 連絡掲示板の表示確認
    public function testBordIndex()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 連絡掲示板画面の非同期通信
        $response = $this->get('/job20/web/bord/' . $this->bord->id)
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // 連絡掲示板画面の表示確認
        $response = $this->get('/bord/' . $this->bord->id)->assertStatus(200);
    }

    // メッセージ新規投稿成功
    public function testMessageCreateSuccess()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 実際にはメールを送らないように設定
        Mail::fake();
        
        // メッセージ投稿
        $response = $this->post('/job20/web/bord/' . $this->bord->id, [
            'bord' => $this->bord,
            'message' => str_repeat('a', 1000),
        ])
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // エラーがないことを確認
        $response->assertSessionHasNoErrors();
        
        // messagesテーブルの登録確認
        $this->assertDatabaseHas('messages', [
            'bord_id' => $this->bord->id,
            'sender_id' => 'user_' . (string) $this->bord->user_id,
            'receiver_id' => 'company_' . (string) $this->bord->company_id,
            'message' => str_repeat('a', 1000),
        ]);

        // メールが1回送信されたことをアサート
        Mail::assertSent(newMessage::class, 1);
    }

    // メッセージ新規投稿失敗
    public function testMessageCreateError()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 未入力のバリデーション
        $response = $this->post('/job20/web/bord/' . $this->bord->id, [
            'message' => '',
        ]);
        $response->assertSessionHasErrors([
            'message' => 'メッセージ内容は必ず入力してください。', 
        ]);
                
        // メッセージ内容の最大文字数のバリデーション
        $response = $this->post('/job20/web/bord/' . $this->bord->id, [
            'message' => str_repeat('a', 1001),
        ]);
        $response->assertSessionHasErrors([
            'message' => 'メッセージ内容は、1000文字以下で指定してください。', 
        ]);
    }
}
