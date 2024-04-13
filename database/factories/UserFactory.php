<?php
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'email' => Str::random(20) . '@gmail.com',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        // 'remember_token' => $faker->password(),
        'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
        'updated_at' => $faker->dateTimeBetween($startDate = '+1 day', $endDate = '+2 day'),
    ];
});
