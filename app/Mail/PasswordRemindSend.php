<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class PasswordRemindSend extends Mailable
{
    use Queueable, SerializesModels;

    private $auth_key;

    /**
     * コンストラクト
     *
     * @param User $user
     */
    public function __construct($auth_key)
    {
        $this->auth_key = $auth_key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('mail@wakisakaryohei.com', 'ジョブインフォ20運営事務局') // 送信元
            ->subject('【ジョブインフォ20】パスワード再発行認証メール') // メールタイトル
            ->view('mail.password_remind_send') // メール本文のテンプレート
            ->with([ // viewへ渡すデータ
                'auth_key' => $this->auth_key,
            ]);
    }
}
