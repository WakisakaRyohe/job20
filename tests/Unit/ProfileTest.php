<?php

namespace Tests\Unit;

use App\Job;
use App\User;
use App\Mail\ConfirmEmail;
use App\Mail\UpdateEmail;
use App\Traits\ModelDeleteTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストユーザ作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    // プロフィール編集画面の表示
    public function testProfileShow()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);
        // プロフィール画面の非同期通信
        $response = $this->get('/job20/web/profile')->assertStatus(200)->assertJsonCount(3);
        // プロフィール画面の表示確認
        $response = $this->get('/profile')->assertStatus(200);
    }

    // プロフィール編集成功
    public function testProfileEditSuccess()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);
                
        // 画像データ作成
        $file = UploadedFile::fake()->image('avatar.jpg', 3800, 4800)->size(6800);

        // 編集するデータ
        $postData1 = [
            'id' => $this->user->id,
            'name' => str_repeat('a', 50),
            'email' => $this->user->email,
            'password_new' => 'newPassword',
            'password_old' => 'password',
            'age' => 20,
            'sex' => '男性',
            'prefecture_id' => 1, 
            'job_career' => str_repeat('a', 500),
            'final_education' => str_repeat('a', 500),
            'certifications' => str_repeat('a', 500),
            'self_promotion' => str_repeat('a', 100),
            'file' => $file,
            'testFlg' => true,
        ];

        // プロフィール編集
        $response = $this->post('/job20/web/profile', $postData1)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // usersテーブルの登録確認
        $dbData1 = [
            'id' => $this->user->id,
            'name' => str_repeat('a', 50),
            'email' => $this->user->email,
            'age' => 20,
            'sex' => '男性',
            'prefecture_id' => 1, 
            'job_career' => str_repeat('a', 500),
            'final_education' => str_repeat('a', 500),
            'certifications' => str_repeat('a', 500),
            'self_promotion' => str_repeat('a', 100),
            'icon_image' => $file->name,
        ];
        $this->assertDatabaseHas('users', $dbData1);
        
        // 画像がストレージに保存されていることを確認
        Storage::disk('s3')->assertExists('job_change_20/' . $file->name);
        
        // エラーがないことを確認
        $response->assertSessionHasNoErrors();
        
        // 画像削除後に存在しないことを確認
        Storage::disk('s3')->delete('/job_change_20/' . $file->name);
        Storage::disk('s3')->assertMissing('job_change_20/' . $file->name);
        
        // ユーザー削除
        $this->userModelDelete();
    }
    
    // プロフィール編集失敗
    public function testProfileEditError()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user);

        // 未入力のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => '',
            'email' => '',
        ]);
        $response->assertSessionHasErrors([
            'name' => 'お名前は必ず入力してください。', 
            'email' => 'メールアドレスは必ず入力してください。', 
        ]);

        // 最大文字数とメールアドレス形式のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => str_repeat('b', 51),
            'email' => str_repeat('b', 51),
            'password_new' => str_repeat('b', 33),
            'password_old' => 'password',
            'job_career' => str_repeat('b', 501),
            'final_education' => str_repeat('b', 501),
            'certifications' => str_repeat('b', 501),
            'self_promotion' => str_repeat('b', 1001),
        ]);
        $response->assertSessionHasErrors([
            'name' => 'お名前は、50文字以下で指定してください。', 
            'email' => 'メールアドレスは、50文字以下で指定してください。', 
            'email' => 'メールアドレスには、有効なメールアドレスを指定してください。', 
            'password_new' => '新しいパスワードは、32文字以下で指定してください。',
            'job_career' => '職務経歴は、500文字以下で指定してください。', 
            'final_education' => '最終学歴は、500文字以下で指定してください。', 
            'certifications' => '資格は、500文字以下で指定してください。', 
            'self_promotion' => '自己PRは、1000文字以下で指定してください。', 
        ]);

        // パスワード最小文字数のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password_new' => str_repeat('b', 7),
            'password_old' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'password_new' => '新しいパスワードは、8文字以上で指定してください。',
        ]);

        // パスワード半角英数のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password_new' => 'ああああああああ',
            'password_old' => 'password',
        ]);
        $response->assertSessionHasErrorsIn('新しいパスワードは半角英数字で入力してください。');
                
        // 新しいパスワードと古いパスワードは同値不可のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password_new' => 'password',
            'password_old' => 'password',
        ]);
        $response->assertSessionHasErrors([
            'password_new' => '新しいパスワードと古いパスワードには、異なった内容を指定してください。',
        ]);

        // 新しいパスワード入力時は、古いパスワード必須のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password_new' => 'password_new',
        ]);
        $response->assertSessionHasErrors([
            'password_old' => '新しいパスワードを指定する場合は、古いパスワードも指定してください。',
        ]);
        
        // 年齢のバリデーションチェック
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'age' => 19,
        ]);
        $response->assertSessionHasErrors([
            'age' => '年齢は、20から29の間で指定してください。',
        ]);
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'age' => 30,
        ]);
        $response->assertSessionHasErrors([
            'age' => '年齢は、20から29の間で指定してください。',
        ]);
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'age' => 'a',
        ]);
        $response->assertSessionHasErrors([
            'age' => '年齢は、20から29の間で指定してください。',
        ]);

        // ここからが画像アップロードのバリデーション

        // 画像サイズのバリデーションチェック
        $file = UploadedFile::fake()->image('avatar.jpg')->size(7340033);
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'file' => $file,
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrorsIn('アイコン画像には、7000 kB以下のファイルを指定してください。');

        // 画像MIMEタイプの場合のバリデーションチェック
        $file = UploadedFile::fake()->image('avatar.pdf');
        $response = $this->post('/job20/web/profile', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'file' => $file,
            'testFlg' => true,
        ]);
        $response->assertSessionHasErrorsIn('アイコン画像にはjpg, png, gif, heicタイプのファイルを指定してください');
        
        // 画像がストレージに保存されていないことを確認
        Storage::disk('s3')->assertMissing('job_change_20/' . $file->name);
        
        // ユーザー削除
        $this->userModelDelete();
    }
}
