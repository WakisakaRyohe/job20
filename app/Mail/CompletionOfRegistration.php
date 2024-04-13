<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompletionOfRegistration extends Mailable
{
    use Queueable, SerializesModels;

    private $params = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
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
          ->subject('【ジョブインフォ20】会員登録完了のお知らせ') // メールタイトル
          ->with('params', $this->params) // viewへ渡すデータ
          ->view('mail.CompletionOfRegistration'); // メール本文のテンプレート
    }
}
