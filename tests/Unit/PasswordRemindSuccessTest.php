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

class PasswordRemindSuccessTest extends TestCase
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

    // パスワードリマインダー成功
    public function testPasswordRemindSuccess()
    {     
        // パスワードリマインダー画面の表示確認
        $response = $this->get('/password_remind_send')->assertStatus(200);

        // 実際にはメールを送らないように設定
        Mail::fake();
        // メールが送られていないことを確認
        Mail::assertNothingSent();
        
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
        
        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // メールが1回送信されたことをアサート
        Mail::assertSent(PasswordRemindSend::class, 1);

        // セッション確認
        $response->assertSessionHas('auth_key')
        ->assertSessionHas('email')
        ->assertSessionHas('limit');

        // パスワード再発行認証の画面の非同期通信
        $response = $this->get('/job20/web/password_remind_edit')
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => true,
            'limitOverFlg' => false,
        ]);

        // パスワード再発行認証の画面の表示確認
        $response = $this->get('/password_remind_receive')->assertStatus(200);

        // 送信するデータ
        $postData = [
            'auth_key' => 'test_auth_key',
            'testFlg' => true,
        ];

        // 認証キー送信
        $response = $this->post('/job20/web/password_remind_receive', $postData)
        ->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment([
            'successFlg' => true,
            'limitOverFlg' => false,
        ]);

        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // メールが1回送信されたことをアサート
        Mail::assertSent(PasswordRemindReceive::class, 1);
        
        // セッションの削除確認
        $response->assertSessionMissing('auth_key');
        $response->assertSessionMissing('email');
        $response->assertSessionMissing('limit');

        // ログイン画面を表示
        $response = $this->get('/login')->assertStatus(200)->assertSee('ログイン');

        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // ログイン実行
        $postData = [
            'email' => $this->user->email,
            'password' => 'newPassword',
        ];
        $response = $this->post('/login', $postData);

        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // 認証されていることを確認
        $this->assertAuthenticatedAs($this->user);

        // 検索ページへのリダイレクトを確認
        $response->assertredirect('/search');

        // ログイン後はログイン画面を表示できない
        $response = $this->get('/login');
        $response->assertStatus(302);     
    }
}
