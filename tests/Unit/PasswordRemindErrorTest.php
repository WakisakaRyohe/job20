<?php

namespace Tests\Unit;

use App\User;
use App\Mail\PasswordRemindSend;
use App\Mail\PasswordRemindReceive;
use App\Traits\ModelDeleteTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordRemindErrorTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストユーザ作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }
    
    public function tearDown(): void
    {
        // ユーザー削除
        $this->userModelDelete();
    }

    // パスワード再設定メール送信失敗
    public function testPasswordRemindSendError()
    {
        // 実際にはメールを送らないように設定
        Mail::fake();
        
        // 未入力のバリデーション
        $response = $this->post('/job20/web/password_remind_send', [
            'email' => '',
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスは必ず入力してください。', 
        ])->assertStatus(302);
        
        // 最大文字数とメールアドレス形式のバリデーション
        $response = $this->post('/job20/web/password_remind_send', [
            'email' => str_repeat('a', 51),
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスは、50文字以下で指定してください。', 
            'email' => 'メールアドレスには、有効なメールアドレスを指定してください。', 
        ])->assertStatus(302);
        
        // メール送信されていないことを確認
        Mail::assertSent(PasswordRemindSend::class, 0);

        // セッションに値がないことを確認
        $response->assertSessionMissing('auth_key')
        ->assertSessionMissing('email')
        ->assertSessionMissing('limit');
    }

    // 未登録のメールアドレスでのエラー
    public function testPasswordRemindNotExistMailError()
    {
        // 実際にはメールを送らないように設定
        Mail::fake();
        
        // 送信するデータ
        $postData = [
            'email' => 'notExist@gmail.com',
            'testFlg' => true,
        ];

        // パスワード再設定メール送信
        $response = $this->post('/job20/web/password_remind_send', $postData)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            $successFlg = true,
        ]);

        // 認証キー送信
        $response = $this->post('/job20/web/password_remind_receive', [
            'auth_key' => 'test_auth_key',
            'testFlg' => true,
            'limitOverFlg' => false,
        ])
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => false,
            'limitOverFlg' => false,
        ]);

        // メールが送信されていないことを確認
        Mail::assertSent(PasswordRemindReceive::class, 0);

        // セッションに値がないことを確認
        $response->assertSessionMissing('auth_key')
        ->assertSessionMissing('email')
        ->assertSessionMissing('limit');
    }
    
    // パスワード再発行の認証失敗
    public function testPasswordRemindAuthKeyError()
    {
        // 実際にはメールを送らないように設定
        Mail::fake();
        
        // 送信するデータ
        $postData = [
            'email' => $this->user->email,
            'testFlg' => true,
        ];

        // パスワード再設定メール送信
        $response = $this->post('/job20/web/password_remind_send', $postData)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            $successFlg = true,
        ]);
        
        // ここから認証キー入力画面

        // 未入力のバリデーション
        $response = $this->post('/job20/web/password_remind_receive', [
            'auth_key' => '',
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrors([
            'auth_key' => '認証キーは必ず入力してください。', 
        ])->assertStatus(302);

        // 認証キー間違いのバリデーション１
        $response = $this->post('/job20/web/password_remind_receive', [
            'auth_key' => 'aaaaaaaa',
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrors([
            'auth_key' => '認証キーが違います。', 
        ])->assertStatus(302);

        // 認証キー間違いのバリデーション２
        $response = $this->post('/job20/web/password_remind_receive', [
            'auth_key' => 'ああああああああ',
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrors([
            'auth_key' => '認証キーが違います。', 
        ])->assertStatus(302);
        
        // メール送信されていないことを確認
        Mail::assertSent(PasswordRemindReceive::class, 0);   
    }

    // パスワード再発行認証画面への不正リクエスト
    public function testPasswordRemindRequestError()
    {
        // パスワード再発行認証の画面の非同期通信
        $response = $this->get('/job20/web/password_remind_edit')
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => false,
            'limitOverFlg' => true,
        ]);
    } 

    // パスワード再発行認証キーの有効期限オーバー
    public function testPasswordRemindLimitOver()
    {
        // 実際にはメールを送らないように設定
        Mail::fake();
        
        // パスワード再設定メール送信
        $response = $this->post('/job20/web/password_remind_send', [
            'email' => $this->user->email,
            'testFlg' => true,
        ])
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            $successFlg = true,
        ]);
        
        // 認証キー送信
        $response = $this->post('/job20/web/password_remind_receive', [
            'auth_key' => 'test_auth_key',
            'testFlg' => true,
            'limitOverFlg' => true,
        ])
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => false,
            'limitOverFlg' => true,
        ]);

        // メールが送信されていないことを確認
        Mail::assertSent(PasswordRemindReceive::class, 0);

        // セッションに値がないことを確認
        $response->assertSessionMissing('auth_key')
        ->assertSessionMissing('email')
        ->assertSessionMissing('limit');
    }
}
