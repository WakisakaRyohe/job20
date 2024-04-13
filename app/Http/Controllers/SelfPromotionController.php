<?php

namespace App\Http\Controllers;

use App\SelfPromotion;
use App\Http\Requests\EditSelfPromotionRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SelfPromotionController extends Controller
{
    // 自己編集画面の表示
    public function edit(Request $request)
    {
        // 最後にreturnするデータを定義
        $self_promotion = null;
        $editFlg = false;
        $successFlg = false;

        try {
            Log::info('自己PR編集画面の表示処理開始。対象アクション：' . __METHOD__);

            $self_promotion = Auth::user()->self_promotion;

            // ユーザーデータ取得の判定
            if(!empty($self_promotion)){
                $successFlg = true;
                $editFlg = true;
                Log::info('自己PRID：' . $self_promotion->id . '、$editFlg:' . $editFlg);

            }else{
                $successFlg = true;
                Log::error('自己PRデータなし。'); 
            }

        } 
        catch(Exception $e) {
            Log::error('自己PR編集画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('自己PR編集画面の表示処理終了。');

        return response()->json([
            'self_promotion' => $self_promotion,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    }    
    
    // 自己PRの更新処理
    public function update(EditSelfPromotionRequest $request)
    {
        // 最後にreturnするデータを定義
        $new_self_promotion = null;
        $editFlg = false;
        $successFlg = false;

        try {
            Log::info('自己PRの登録/更新処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);
                    
                if($user->self_promotion){
                    $self_promotion = $user->self_promotion;
                    $editFlg = true;
                    Log::info('自己PR更新');
                }else{
                    $self_promotion = new SelfPromotion();
                    Log::info('自己PR新規登録');
                }

                // 自己PRテーブルのレコードに外部キーも設定する
                $new_self_promotion = $user->self_promotion()->save($self_promotion->fill($request->all()));               
                Log::info('$new_self_promotion' . $new_self_promotion);

                if(!empty($new_self_promotion)){
                    Log::info('自己PRのID:' . $new_self_promotion->id);
                    $successFlg = true;

                }else{
                    Log::error('自己PRの登録/更新処理失敗。'); 
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('自己PRの登録/更新処理失敗。error_message = ' . $e);
        }

        Log::info('自己PRの登録/更新処理終了。');

        return response()->json([
            'new_self_promotion' => $new_self_promotion,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    } 
}
