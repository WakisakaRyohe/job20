<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {           
        for($i = 1; $i <= 5;  ++$i){
            DB::table('users')->insert([
                'email' => $faker->unique()->email(),
                'email_verified_at' => now(),
                'password' => Hash::make(11111111),
                'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
                'updated_at' => $faker->dateTimeBetween($startDate = '+1 day', $endDate = '+2 day'),
            ]);
        }        
        // factory(User::class, 10)->create();
    }
}
