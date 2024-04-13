<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ExampleTest extends TestCase
{    
    use RefreshDatabase;

    public function testBasicTest()
    {
        $this->WithoutMIddleware([Authenticate::class]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // 画面表示テスト
        $this->get('/')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/search')->assertOk();
        $this->get('/profile')->assertOk();
        $this->get('/job/new')->assertOk();
        $this->get('/job/634')->assertOk();
        $this->get('/job/aaa')->assertOk();
        $this->get('/job/634/edit')->assertOk();
        $this->get('/job/aaa/edit')->assertOk();
        $this->get('/search')->assertOk();
        $this->get('/search?industry=5&occupation=4&employment_status=7')->assertOk();
        $this->get('/bought')->assertOk();
        $this->get('/bookmark')->assertOk();
        $this->get('/myjob')->assertOk();
        $this->get('/customer_review')->assertOk();
        $this->get('/my_review')->assertOk();
        $this->get('/review/879/edit')->assertOk();
        $this->get('/withdrawal')->assertOk();
        $this->get('/logout')->assertOk();
        $this->get('/password_remind_send')->assertOk();
        $this->get('/password_remind_receive')->assertOk();
        $this->get('/aaa')->assertOk();

        // コンテンツに含まれるテキストのテスト
        $this->get('/')->assertSeeText('Inspiration');

        // レスポンスに含まれるテキストのテスト
        $this->get('/')->assertSee('<main>');

        // 用意したテキストが順に登場するかのテスト
        $this->get('/')->assertSeeInEmploymentStatus(['<html','<head','<body']);

        $this->get('/job20/web/job/new')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'name' => 'マッチング',
                'name' => '掲示板',
                'name' => 'SNS',
                'name' => 'シェアリング',
                'name' => 'ECサイト',
                'name' => 'Eラーニング',
                'name' => '口コミサイト',
                'name' => '情報発信',
                'name' => 'その他',
            ]);

        $this->get('/job20/web/job/')->assertSeeText('');
    }
}
