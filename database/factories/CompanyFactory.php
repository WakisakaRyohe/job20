<?php
use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'company_name' => $faker->text(50),
        'companies_industry_id' => $faker->numberBetween(1, 12),
        'address' => $faker->address(),
        'business_content' => $faker->realText(100),
        'company_email' => $faker->unique()->email(),
        'company_icon_image' => 'company_default.jpg',
        'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
        'updated_at' => $faker->dateTimeBetween($startDate = '+1 day', $endDate = '+2 day'),
    ];
});
