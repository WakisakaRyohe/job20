<?php

namespace Tests\Unit;

use Tests\TestCase;

class ToppageTest extends TestCase
{    
    // トップページ表示
    public function testToppage()
    {
        // トップページ画面を表示
        $response = $this->get('')
        ->assertStatus(200)
        ->assertSee('ジョブインフォ20');
    
        // 非同期通信の確認
        $response = $this->get('/job20/web/toppage')
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'successFlg' => true,
            ]);
    }
}
