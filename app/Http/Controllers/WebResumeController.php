<?php

namespace App\Http\Controllers;

use App\Traits\WebResumeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebResumeController extends Controller
{
    use WebResumeTrait;

    public function __invoke(Request $request)
    {
        // 最後にreturnするデータを定義
        $user = null;
        $successFlg = false;

        Log::info('WEB履歴書画面の表示処理開始。対象アクション：' . __METHOD__);

        // WEB履歴書情報を取得
        $data = $this->getWebResume();
        $user = $data['user'];
        $successFlg= $data['successFlg'];

        Log::info('WEB履歴書画面の表示処理終了。');

        return response()->json([
            'user' => $user,
            'successFlg' => $successFlg,
        ]);
    }
}
