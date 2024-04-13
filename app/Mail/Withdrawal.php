<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Withdrawal extends Mailable
{
    use Queueable, SerializesModels;

    // メールで使う変数がないため、「$params」未定義
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this
          ->from('mail@wakisakaryohei.com', 'ジョブインフォ20運営事務局') // 送信元
          ->subject('【ジョブインフォ20】退会完了通知メール') // メールタイトル
          ->view('mail.Withdrawal'); // メール本文のテンプレート
    }
}
