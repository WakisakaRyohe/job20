<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// // 一覧画面の表示
// Route::get('/user',function (Request $request) {
// 	$users = App\User::all();
// 	return response()->json(['users' => $users]);
// });

// // ユーザー作成処理
// Route::post('/user', function(Request $request){
// 	$user = App\User::create($request->user);
// 	return response()->json(['user' => $user]);

// });

// // 詳細画面の表示
// Route::get('/user/{user}', function(App\User $user){
// 	return response()->json(['user' => $user]);
// });

// // 編集処理
// Route::patch('/user/{user}', function(App\User $user,Request $request){
// 	$user->update($request->user);
// 	return response()->json(['user' => $user]);

// });

// // 削除処理
// Route::delete('/user/{user}', function(App\User $user){
// 	$user->delete();
// 	return response()->json(['message' => 'delete successfully']);
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
