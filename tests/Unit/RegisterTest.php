<?php

namespace Tests\Unit;

use App\User;
use App\Mail\CompletionOfRegistration;
use App\Traits\ModelDeleteTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RegisterTest extends TestCase
{      
    use ModelDeleteTrait;
  
    // テストユーザ作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }
    
    // 会員登録成功
    public function testRegisterSuccess()
    {
        // 会員登録画面を表示する
        $response = $this->get('/register');
        $response->assertStatus(200)->assertSee('会員登録');

        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // 実際にはメールを送らないように設定
        Mail::fake();
        // メールが送られていないことを確認
        Mail::assertNothingSent();
        
        $postData = [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // 会員登録実行
        $response = $this->post('/register', $postData);

        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // メールが1回送信されたことをアサート
        Mail::assertSent(CompletionOfRegistration::class, 1);
        
        // usersテーブルの登録確認
        $this->assertDatabaseHas('users', [
            'id' => ($this->user->id) + 1,
            'name' => 'Test User',
            'email' => 'test@gmail.com',
        ]);
        
        // 認証されていることを確認
        $this->assertTrue(Auth::check());

        // 検索ページへのリダイレクトを確認
        $response->assertredirect('/search');

        // ログイン後は会員登録画面を表示できない
        $response = $this->get('/register');
        $response->assertStatus(302);
    }

    // 会員登録失敗
    public function testRegisterError()
    {
        // 会員登録画面を表示
        $response = $this->get('/register');
        $response->assertStatus(200)->assertSee('会員登録');

        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // 未入力のバリデーションチェック
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertSessionHasErrors([
            'name' => 'お名前は必ず入力してください。',
            'email' => 'メールアドレスは必ず入力してください。', 
            'password' => 'パスワードは必ず入力してください。',
            'password_confirmation' => '再入力パスワードは必ず入力してください。',
        ]);
        
        // 最大文字数のバリデーションチェック
        $response = $this->post('/register', [
            'name' => str_repeat('a', 51),
            'email' => str_repeat('a', 51),
            'password' => str_repeat('a', 33),
            'password_confirmation' => str_repeat('a', 1),
        ]);
        $response->assertSessionHasErrors([
            'name' => 'お名前は、50文字以下で指定してください。', 
            'email' => 'メールアドレスには、有効なメールアドレスを指定してください。', 
            'email' => 'メールアドレスは、50文字以下で指定してください。', 
            'password' => 'パスワードは、32文字以下で指定してください。',
            'password_confirmation' => '再入力パスワードとパスワードには同じ値を指定してください。',
        ]);
        
        // メールアドレス形式のバリデーションチェック
        $response = $this->post('/register', [
            'name' => 'Test User1',
            'email' => str_repeat('a', 50),
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスには、有効なメールアドレスを指定してください。', 
        ]);
        
        // 登録済みメールアドレスのバリデーションチェック
        $response = $this->post('/register', [
            'name' => 'Test User1',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスの値は既に存在しています。', 
        ]);        

        // パスワード最小文字数のバリデーションチェック
        $response = $this->post('/register', [
            'name' => 'Test User1',
            'email' => 'test1@gmail.com',
            'password' => str_repeat('a', 7),
            'password_confirmation' => str_repeat('a', 7),
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは、8文字以上で指定してください。',
        ]);
              
        // パスワード半角英数のバリデーションチェック
        $response = $this->post('/register', [
            'name' => 'Test User1',
            'email' => 'test1@gmail.com',
            'password' => 'ああああああああ',
            'password_confirmation' => 'ああああああああ',
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは半角英数字で入力してください。',
        ]);
        
        // 同値のバリデーションチェック
        $response = $this->post('/register', [
            'name' => 'Test User1',
            'email' => 'test1@gmail.com',
            'password' => 'password',
            'password_confirmation' => str_repeat('a', 8),
        ]);
        $response->assertSessionHasErrors([
            'password_confirmation' => '再入力パスワードとパスワードには同じ値を指定してください。',
        ]);

        // usersテーブルに登録されていないことを確認
        $this->assertDatabaseMissing('users', [
            'name' => 'Test User1',
            'email' => 'test1@gmail.com',
        ]);
        
        // 認証されていないことを確認
        $this->assertFalse(Auth::check());

        // ユーザー削除
        $this->userModelDelete();
    }    
}
