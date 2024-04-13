<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
      return $this
          ->from('mail@wakisakaryohei.com', 'ジョブインフォ20運営事務局') // 送信元
          ->subject('【ジョブインフォ20】メールアドレス変更完了のご案内') // メールタイトル
          ->view('mail.UpdateEmail'); // メール本文のテンプレート
    }
}
