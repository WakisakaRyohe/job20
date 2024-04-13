<?php

use Illuminate\Database\Seeder;

class EmploymentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'employment_status_name' => '正社員'],
            ['id' => 2, 'employment_status_name' => '契約社員'],
            ['id' => 3, 'employment_status_name' => '業務委託'],
            ['id' => 4, 'employment_status_name' => 'パート・アルバイト'],
            ['id' => 5, 'employment_status_name' => '派遣社員'],
        ];
        DB::table('employment_statuses')->insert($params);
    }
}
