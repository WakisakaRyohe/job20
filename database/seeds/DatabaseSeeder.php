<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 検索用
        $this->call(EmploymentStatusesTableSeeder::class);
        $this->call(IndustriesTableSeeder::class);
        $this->call(OccupationsTableSeeder::class);
        $this->call(PrefecturesTableSeeder::class);
        $this->call(AnnualIncomesTableSeeder::class);
        $this->call(OrdersTableSeeder::class);

        // ダミーデータ作成
        $this->call(UsersTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(JobsTableSeeder::class);        
    }
}
