<?php

namespace App\Http\Controllers;

use App\Skill;
use App\Http\Requests\EditSkillRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SkillController extends Controller
{
    // 資格・スキル編集画面の表示
    public function edit(Request $request)
    {
        // 最後にreturnするデータを定義
        $skill = null;
        $editFlg = false;
        $successFlg = false;        

        try {
            Log::info('資格・スキル編集画面の表示処理開始。対象アクション：' . __METHOD__);

            $skill = Auth::user()->skill;

            // ユーザーデータ取得の判定
            if(!empty($skill)){
                $editFlg = true;
                Log::info('資格・スキルID：' . $skill->id . '、$editFlg:' . $editFlg);

                $certificationsName = $skill->certifications;
                Log::info('資格の文字列：' . $certificationsName);

                // 資格データがある場合
                if(!empty($certificationsName)){
                    Log::info('資格データあり');

                    // 資格が１つの場合(資格の文字列に「,」が含まれていない)
                    if( ! str_contains($certificationsName, ',') ) {
                        Log::info('資格が１つの場合');

                        $newArray = [];
                        array_push($newArray, $certificationsName);
    
                    // 資格が複数の場合
                    }else{
                        Log::info('資格が複数の場合');
                        // 複数の資格が「,」区切りの文字列でDBに保存されているので、配列に変換
                        $newArray = explode(",", $skill->certifications);
                    }
    
                    Log::info('資格数：'. count($newArray));
                    
                    // 配列化した資格の文字列をセット
                    $skill->certifications = $newArray;
                }

            }else{
                Log::error('資格・スキルデータなし。'); 
            }

            $successFlg = true;
        } 
        catch(Exception $e) {
            Log::error('資格・スキル編集画面の表示処理失敗。error_message = ' . $e);
        }

        Log::info('資格・スキル編集画面の表示処理終了。');

        return response()->json([
            'skill' => $skill,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    }    
    
    // 資格・スキルの更新処理
    public function update(EditSkillRequest $request)
    {
        // 最後にreturnするデータを定義
        $newSkill = null;
        $editFlg = false;
        $successFlg = false;        

        try {
            Log::info('資格・スキルの登録/更新処理開始。対象アクション：' . __METHOD__);

            $user = Auth::user();

            // ユーザーデータ取得の判定
            if(!empty($user)){
                Log::info('ユーザーID：' . $user->id);
                    
                if($user->skill){
                    $skill = $user->skill;
                    $editFlg = true;
                    Log::info('資格・スキル更新');
                }else{
                    $skill = new Skill();
                    Log::info('資格・スキル新規登録');
                }
                Log::info('資格数：' . count($request->certifications));

                // 資格データがある場合
                if(count($request->certifications)){
                    // 資格の配列を「,」区切りで文字列化
                    $stringCertifications = implode(',', $request->certifications);
                    // 資格の文字列をレコードにセット
                    $skill->certifications = $stringCertifications;
                    Log::info('文字列化した資格の配列：' . $skill->certifications);

                // 資格データがない場合
                }else{
                    // 空文字をレコードにセット
                    $skill->certifications = '';
                }

                // 資格・スキルテーブルのレコードに外部キーも設定する
                $newSkill = $user->skill()->save($skill->fill($request->except('certifications')));               
                Log::info('$newSkill' . $newSkill);

                if(!empty($newSkill)){
                    Log::info('資格・スキルID:' . $newSkill->id);
                    $successFlg = true;        

                }else{
                    Log::error('資格・スキルの登録/更新処理失敗。'); 
                }
            }else{
                Log::error('ユーザーデータ取得失敗。'); 
            }
        } 
        catch(Exception $e) {
            Log::error('資格・スキルの登録/更新処理失敗。error_message = ' . $e);
        }

        Log::info('資格・スキルの登録/更新処理終了。');

        return response()->json([
            'newSkill' => $newSkill,
            'editFlg' => $editFlg,
            'successFlg' => $successFlg,
        ]);
    }    
}
