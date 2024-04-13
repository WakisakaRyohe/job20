<?php

use Illuminate\Database\Seeder;

class OccupationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'occupation_name' => '営業'],
            ['id' => 2, 'occupation_name' => '事務・管理・企画・経営'],
            ['id' => 3, 'occupation_name' => '販売・フード・アミューズメント'],
            ['id' => 4, 'occupation_name' => '美容・ブライダル・ホテル・交通'],
            ['id' => 5, 'occupation_name' => 'WEB・インターネット・ゲーム'],
            ['id' => 6, 'occupation_name' => 'クリエイティブ'],
            ['id' => 7, 'occupation_name' => 'ITエンジニア'],
            ['id' => 8, 'occupation_name' => '電気・電子・機械・半導体'],
            ['id' => 9, 'occupation_name' => '医薬・食品・化学・素材'],
            ['id' => 10, 'occupation_name' => '建築・土木'],
            ['id' => 11, 'occupation_name' => 'コンサルタント・金融・不動産専門職・士業'],
            ['id' => 12, 'occupation_name' => '医療・福祉'],
            ['id' => 13, 'occupation_name' => '保育・教育・通訳'],
            ['id' => 14, 'occupation_name' => 'その他'],
        ];
        DB::table('occupations')->insert($params);
    }
}
