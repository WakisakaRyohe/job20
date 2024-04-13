<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class PasswordRemindReceive extends Mailable
{
    use Queueable, SerializesModels;

    private $pass;

    /**
     * コンストラクト
     *
     * @param User $user
     */
    public function __construct($pass)
    {
        $this->pass = $pass;
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
            ->subject('【ジョブインフォ20】パスワード再発行完了メール') // メールタイトル
            ->view('mail.password_remind_receive') // メール本文のテンプレート
            ->with([ // viewへ渡すデータ
                'pass' => $this->pass,
            ]);
    }
}
