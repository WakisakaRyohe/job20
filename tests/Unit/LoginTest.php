<?php

namespace Tests\Unit;

use App\User;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
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

    // ログイン成功
    public function testLoginSuccess()
    {
        // ログイン画面を表示
        $response = $this->get('/login');
        $response->assertStatus(200)->assertSee('ログイン');

        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // usersテーブルの登録確認
        $user = User::where('id', $this->user->id)->count();
        $this->assertEquals($user, 1);
        
        // ログイン実行
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // 認証されていることを確認
        $this->assertAuthenticatedAs($this->user);
        // $this->assertTrue(Auth::check());

        // 検索ページへのリダイレクトを確認
        $response->assertredirect('/search');

        // ログイン後はログイン画面を表示できない
        $response = $this->get('/login');
        $response->assertStatus(302);
    }

    // ログイン失敗
    public function testLoginError()
    {
        // ログイン画面を表示
        $response = $this->get('/login');
        $response->assertStatus(200)->assertSee('ログイン');

        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // 未入力のバリデーションチェック
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスは必ず入力してください。', 
            'password' => 'パスワードは必ず入力してください。',
        ]);
                
        // 最大文字数のバリデーションチェック
        $response = $this->post('/login', [
            'email' => str_repeat('a', 51),
            'password' => str_repeat('a', 33),
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスには、有効なメールアドレスを指定してください。', 
            'email' => 'メールアドレスは、50文字以下で指定してください。', 
            'password' => 'パスワードは、32文字以下で指定してください。'
        ]);
                
        // メールアドレス形式のバリデーションチェック
        $response = $this->post('/login', [
            'email' => 'test@gmail.co',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスには、有効なメールアドレスを指定してください。', 
        ]);

        // メールアドレス間違いのバリデーションチェック
        $response = $this->post('/login', [
            'email' => 'test1@gmail.com',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスまたはパスワードが違います。'
        ]);
        
        // パスワード最小文字数のバリデーションチェック
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => str_repeat('a', 7),
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、8文字以上で指定してください。'
        ]);

        // パスワード半角英数のバリデーションチェック
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'ああああああああ',
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは半角英数字で入力してください。',
        ]);
        
        // パスワード間違いのバリデーションチェック
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password1',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスまたはパスワードが違います。'
        ]);
        
        // 認証されていないことを確認
        $this->assertFalse(Auth::check());
    }
    
    // ログアウト
    public function testLogout()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // ログアウトページへリクエストを送信
        $response = $this->post('/logout');

        // トップページへのリダイレクトを確認
        $response->assertredirect('');

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }
}
