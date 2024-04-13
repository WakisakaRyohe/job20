<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $companies = [
            [
                'name' => 'ファルコン投信投資顧問',
                'business_content' => '業務システム開発、スマートフォンアプリ開発
                コンピューターソフトウェアに関する教育、技術指導及び研修',
            ],
            [
                'name' => '佐土原(さどはら)公共システムエンジニアリング',
                'business_content' => 'ソフトウェアの企画開発、販売、保守、及びコンサルティング
                ホームページ制作、保守運用管理、ロゴ・バナー制作',
            ],
            [
                'name' => 'バリスピラ事務所',
                'business_content' => '●人材サービス事業
                ●会計ポータルサイトの運営
                ●M&A支援事業　など',
            ],
            [
                'name' => 'ミュルンヒア・スター・トラック',
                'business_content' => '■建築積算業務
                ■建築コストマネジメント
                ■建築バリューエンジニアリング
                ■建築・土木工事一般',
            ],
            [
                'name' => '高家(たかや)設計',
                'business_content' => '■建築積算業務
                ■建築コストマネジメント
                ■建築バリューエンジニアリング
                ■建築・土木工事一般',
            ],
            [
                'name' => '天保(あまやす)ツアーズ',
                'business_content' => '名刺、パンフレット、チラシ、ショップカード等のDTP制作',
            ],
            [
                'name' => '学校法人 パーダテチア&ガジュマル',
                'business_content' => '■広告運用
                デジタル広告の掲載手法はテクノロジーにより高度化され、広告枠への広告掲載が入札によって決まる方式があります。
                依頼主の広告予算と達成基準をもとに、効果的な広告掲載を追求する、「運用」を行っています。',
            ],
            [
                'name' => 'アスペル',
                'business_content' => '■医療機器・医療材料販売
                医療機関に医療機器・医療用消耗品の販売・納入・新商品の提案を行う',
            ],
            [
                'name' => '金照(かねてる)',
                'business_content' => '●人材サービス事業
                ●会計ポータルサイトの運営
                ●M&A支援事業　など',
            ],
            [
                'name' => 'スリヴェン・コーヒー',
                'business_content' => '●室内装飾及び化合成品の製造販売業',
            ],
        ];

        foreach($companies as $key => $val){
            DB::table('companies')->insert([
                'company_name' => $val['name'],
                'companies_industry_id' => $faker->numberBetween(1, 12),
                'address' => $faker->address(),
                'business_content' => $val['business_content'],
                'company_email' => $faker->unique()->email(),
                'company_icon_image' => 'company_image' . ($key + 1) . '.jpg',
                'created_at' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 hour'),
                'updated_at' => $faker->dateTimeBetween($startDate = '+1 day', $endDate = '+2 day'),
            ]);
        }        
    }
}
