<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // .env の APP_ENV が production（本番環境）でない場合
        if(config('app.env') !== 'production') {
            // ログ出力設定
            DB::listen (function ($query) {
                Log::info( "Query time:{$query->time}sl $query->sql" );
            });
        }
    }
}
