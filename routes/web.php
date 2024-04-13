<?php
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;

// トップページ
Route::get('/web/toppage', 'ToppageController')->name('toppage');

// 会員登録・ログイン
Auth::routes();

// パスワードリマインダー
Route::post('/web/password_remind_send', 'PasswordRemindController@send')->name('password_remind.send');
Route::get('/web/password_remind_edit', 'PasswordRemindController@edit')->name('password_remind.edit');
Route::post('/web/password_remind_receive', 'PasswordRemindController@update')->name('password_remind.update');

// お問い合わせ
Route::post('/web/contact', 'ContactController')-> name('contact');

// ログインユーザーのみ表示
Route::group(['middleware' => 'auth'], function() {
    // 各種設定
    Route::get('/web/setting', 'SettingController')-> name('setting');
    // メールアドレス変更
    Route::get('/web/change_email', 'ChangeEmailController@edit')-> name('change_email.edit');
    Route::post('/web/change_email', 'ChangeEmailController@update')-> name('change_email.update');
    Route::get('/web/complete_update_email', 'ChangeEmailController@complete')->name('change_email.complete');
    // パスワード変更
    Route::get('/web/change_password', 'ChangePasswordController@edit')-> name('change_password.edit');
    Route::post('/web/change_password_check', 'ChangePasswordController@check')-> name('change_password.check');
    Route::post('/web/change_password', 'ChangePasswordController@update')-> name('change_password.update');
    // 退会
    Route::post('/web/withdrawal', 'WithdrawalController')->name('withdrawal');
    
    // WEB履歴書
    Route::get('/web/web_resume', 'WebResumeController')-> name('web_resume');
    // プロフィール編集
    Route::get('/web/profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('/web/profile', 'ProfileController@update')->name('profile.update');
    // 職務経歴編集
    Route::get('/web/job_career/{id}', 'JobCareerController@edit')-> name('job_career.edit');
    Route::post('/web/job_career/{id}', 'JobCareerController@update')-> name('job_career.update');
    Route::delete('/web/job_career/{id}', 'JobCareerController@delete')-> name('job_career.delete');
    // 資格・スキル編集
    Route::get('/web/skill', 'SkillController@edit')-> name('skill.edit');
    Route::post('/web/skill', 'SkillController@update')-> name('skill.password');
    // 自己PR編集
    Route::get('/web/self_promotion', 'SelfPromotionController@edit')-> name('self_promotion.edit');
    Route::post('/web/self_promotion', 'SelfPromotionController@update')-> name('self_promotion.password');
    
    // 求人検索
    Route::post('/web/index', 'SearchController@index')->name('search.index');
    Route::post('/web/search', 'SearchController@search')->name('search.search');

    // 求人詳細
    Route::get('/web/job/{id}', 'JobController@show')->name('job.show');
    // 求人応募
    Route::post('/web/job/{id}', 'JobController@apply')->name('job.apply');
    
    // 気になる求人一覧
    Route::post('/web/bookmark_jobs', 'BookmarkJobsController')->name('bookmark_jobs');
    // 気になる求人の確認・登録・削除
    Route::get('/web/bookmark/{id}', 'BookmarkController@check')->name('bookmark.check');
    Route::post('/web/bookmark/{id}', 'BookmarkController@store')->name('bookmark.store');
    Route::post('/web/bookmark/{id}/delete', 'BookmarkController@destroy')->name('bookmark.delete');
        
    // メッセージ一覧
    Route::get('/web/messages', 'MessagesController')->name('messages');
    // 連絡掲示板
    Route::get('/web/bord/{id}', 'BordController@index')->name('bord.index');
    Route::post('/web/bord/{id}', 'BordController@create')->name('bord.create');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any','.*');

// Route::get('/home', 'HomeController@index')->name('home');
