<?php
use App\Job;
use App\Company;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'job_name' => $faker->text(50),
        "thumbnail" => 'thumbnail1.jpg',
        'job_summary' => $faker->realText(300),
        'job_description' => $faker->realText(1000),
        'statue' => '●責任感をもって仕事に取り組める方                    
        ●進んで行動に移すことができる方',
        'work_location' => $faker->address(),
        'annual_income' => $faker->numberBetween(2000000, 7000000),
        'basic_salary' => $faker->numberBetween(200000, 500000),
        'overtime_pay' => '固定残業代（31,700円～、20時間相当分）
        ※時間超過分は追加支給',
        'salary_increase' => '【昇給】年1回
        【賞与】年2回 （昨年度実績：2回合計で平均月給4,2ヶ月分）',
        'allowance' => '交通費(全額支給)/残業手当/営業手当/資格手当/休日勤務手当
        ●交通費支給（月15万円まで）
        ●生計補助手当（月2万円／配偶者を有する世帯主に支給）',
        'working_hours' => '就業形態：定時間
        【就業時間】9：00～18：00
        【実働】8時間00分
        【休憩】60分
        【残業】月10～20時間程度',
        'holiday' => '休日休暇
        ‥☆年間休日120日以上☆‥
        
        完全週休2日制（土・日）
        祝日
        夏季休暇
        年末年始休暇
        有給休暇',
        'welfare' => '健康保険/厚生年金保険/雇用保険/労災保険',
        'application_conditions' => '●大卒以上
        ●社会人経験1年以上',
        'selection' => '書類選考→一次面接→最終選考→内定',
        'company_id' => function() {
            return factory(Company::class)->create()->id;
        },
        'industry_id' => $faker->numberBetween(1, 12),
        'occupation_id' => $faker->numberBetween(1, 14),
        'employment_status_id' => $faker->numberBetween(1, 6),
        'prefecture_id' => $faker->numberBetween(1, 47),
        'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
        'updated_at' => $faker->dateTimeBetween($startDate = '+1 hour', $endDate = '+1 day'),
    ];
});
