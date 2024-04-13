<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//あとでログを出力する処理もいれるので
use Illuminate\Support\Facades\Log;
//操作するテーブルを読み込む
use App\User;

class UserCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ユーザー数を取得';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      //標準出力&ログに出力するメッセージのフォーマット
      $message = '[' . date('Y-m-d h:i:s') . ']UserCount:' . User::count();

      //INFOレベルでメッセージを出力
      $this->info( $message );
      //ログを書き出す処理はこちら
      Log::setDefaultDriver('batch');
      Log::info( $message );
    }
}
