<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'order_name' => '投稿日が新しい順'],
            ['id' => 2, 'order_name' => '投稿日が古い順'],
            ['id' => 3, 'order_name' => '年収が高い順'],
            ['id' => 4, 'order_name' => '年収が低い順'],
        ];
        DB::table('orders')->insert($params);
    }
}
