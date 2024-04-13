<?php
use App\User;
use App\Job;
use App\Bookmark;
use Faker\Generator as Faker;

$factory->define(Bookmark::class, function (Faker $faker) {
    return [
        'job_id' => function() {
            return factory(Job::class)->create()->id;
        },
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
        'updated_at' => $faker->dateTimeBetween($startDate = '+1 hour', $endDate = '+1 day'),
    ];
});
