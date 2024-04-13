<?php
use App\Bord;
use App\Company;
use App\Job;
use App\User;
use Faker\Generator as Faker;

$factory->define(Bord::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'company_id' => function() {
            return factory(Company::class)->create()->id;
        },
        'job_id' => function() {
            return factory(Job::class)->create()->id;
        },
        'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
        'updated_at' => $faker->dateTimeBetween($startDate = '+1 hour', $endDate = '+1 day'),
    ];
});
