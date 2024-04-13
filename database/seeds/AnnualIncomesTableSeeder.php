<?php

use Illuminate\Database\Seeder;

class AnnualIncomesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'annual_income_name' => '200万円〜300万円'],
            ['id' => 2, 'annual_income_name' => '300万円〜400万円'],
            ['id' => 3, 'annual_income_name' => '400万円〜500万円'],
            ['id' => 4, 'annual_income_name' => '500万円〜600万円'],
            ['id' => 5, 'annual_income_name' => '600万円〜700万円'],
        ];
        DB::table('annual_incomes')->insert($params);
    }
}
