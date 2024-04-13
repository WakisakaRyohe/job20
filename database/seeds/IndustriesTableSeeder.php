<?php

use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'industry_name' => 'IT・通信・インターネット'],
            ['id' => 2, 'industry_name' => 'メーカー'],
            ['id' => 3, 'industry_name' => '商社'],
            ['id' => 4, 'industry_name' => 'サービス・レジャー'],
            ['id' => 5, 'industry_name' => '流通・小売・フード'],
            ['id' => 6, 'industry_name' => 'マスコミ・広告・デザイン'],
            ['id' => 7, 'industry_name' => '金融・保険'],
            ['id' => 8, 'industry_name' => 'コンサルティング'],
            ['id' => 9, 'industry_name' => '不動産・建設・設備'],
            ['id' => 10, 'industry_name' => '運輸・交通・物流・倉庫'],
            ['id' => 11, 'industry_name' => '環境・エネルギー'],
            ['id' => 12, 'industry_name' => '公的機関・その他'],
        ];
        DB::table('industries')->insert($params);
    }
}
