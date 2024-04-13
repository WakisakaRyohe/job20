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

class UpdateEmailTest extends TestCase
{    
    use ModelDeleteTrait;

    // テストユーザ作成
    public function setUp(): void
    {
        parent::setUp();
        $this->user1 = factory(User::class)->create();
        $this->user2 = factory(User::class)->create();
    }

    // メールアドレス変更成功
    public function testUpdateEmailSuccess()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user1);

        // 実際にはメールを送らないように設定
        Mail::fake();

        // 編集するデータ
        $postData1 = [
            'id' => $this->user1->id,
            'name' => $this->user1->name,
            'email' => 'update@gmail.com',
            'testFlg' => true,
        ];
        
        // プロフィール編集
        $response = $this->post('/job20/web/profile', $postData1)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);
        
        // 確認メールが送信されたことをアサート
        Mail::assertSent(ConfirmEmail::class, 1);
        
        // セッション情報の確認
        $response->assertSessionHas('auth_key', 'auth_key');
        $response->assertSessionHas('old_email', $this->user1->email);
        $response->assertSessionHas('new_email', 'update@gmail.com');
        $response->assertSessionHas('limit');

        // ここからメールアドレス変更完了画面

        // メールアドレス変更完了画面の非同期通信
        $response = $this->get('/job20/web/update_email')
        ->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonFragment([
            'successFlg' => true,
            'unAuthFlg'=> false,
            'limitOverFlg' => false,
        ]);

        // メールアドレス変更完了画面の表示確認
        $response = $this->get('/update_email')->assertStatus(200);

        // 完了メールが送信されたことをアサート
        Mail::assertSent(UpdateEmail::class, 2);

        // セッションに値がないことを確認
        $response->assertSessionMissing('auth_key')
        ->assertSessionMissing('old_email')
        ->assertSessionMissing('new_email')
        ->assertSessionMissing('limit');

        // usersテーブルの登録確認（メールアドレス変更後）
        $dbData1 = [
            'id' => $this->user1->id,
            'name' => $this->user1->name,
            'email' => 'update@gmail.com',
        ];
        $this->assertDatabaseHas('users', $dbData1);

        // ユーザー削除
        $this->userModelDelete();
    }    
    
    // メールアドレス変更失敗（登録済みメールアドレス）
    public function testUpdateEmailError()
    {
        // actingAsヘルパで現在認証済みのユーザーを指定する
        $response = $this->actingAs($this->user1);
        
        // 実際にはメールを送らないように設定
        Mail::fake();
        
        // 編集するデータ（登録済みメールアドレス入力）
        $postData2 = [
            'id' => $this->user1->id,
            'name' => $this->user1->name,
            'email' => $this->user2->email,
            'testFlg' => true,
        ];

        // プロフィール編集
        $response = $this->post('/job20/web/profile', $postData2)
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'successFlg' => true,
        ]);

        // usersテーブルの登録確認（メールアドレス変更前）
        $dbData2 = [
            'id' => $this->user1->id,
            'name' => $this->user1->name,
            'email' => $this->user1->email,
        ];
        $this->assertDatabaseHas('users', $dbData2);
                
        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // 確認メールが送信されたことをアサート
        Mail::assertSent(ConfirmEmail::class, 1);
        
        // セッション情報の確認
        $response->assertSessionHas('auth_key', 'auth_key');
        $response->assertSessionHas('old_email', $this->user1->email);
        $response->assertSessionHas('new_email', $this->user2->email);
        $response->assertSessionHas('limit');

        // ここからメールアドレス変更完了画面

        // メールアドレス変更完了画面の非同期通信（登録済みのメールアドレスなのでエラーになっている）
        $response = $this->get('/job20/web/update_email')
        ->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonFragment([
            'successFlg' => false,
            'unAuthFlg'=> false,
            'limitOverFlg' => false,
        ]);

        // メールアドレス変更完了画面の表示確認
        $response = $this->get('/update_email')->assertStatus(200);

        // セッションに値がないことを確認
        $response->assertSessionMissing('auth_key')
        ->assertSessionMissing('old_email')
        ->assertSessionMissing('new_email')
        ->assertSessionMissing('limit');

        // ユーザー削除
        $this->userModelDelete();
    }
}
