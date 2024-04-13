<?php
namespace App\Traits;

use App\AnnualIncome;
use App\Company;
use App\EmploymentStatus;
use App\Industry;
use App\Occupation;
use App\Order;
use App\Prefecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait SearchBoxTrait
{
    public static function getSearchBoxData()
    {
        // 検索用データ取得
        $industries = Industry::all();
        $industriesCountNum = $industries->count();

        $occupations = Occupation::all();
        $occupationsCountNum = $occupations->count();

        $employment_statuses = EmploymentStatus::all();
        $employment_statusesCountNum = $employment_statuses->count();

        $prefectures = Prefecture::all();
        $prefecturesCountNum = $prefectures->count();

        $annual_incomes = AnnualIncome::all();
        $annual_incomesCountNum = $annual_incomes->count();

        $orders = Order::all();
        $ordersCountNum = $orders->count();

        if($industriesCountNum >= 1 && $occupationsCountNum >= 1 && $employment_statusesCountNum >= 1 && 
        $prefecturesCountNum >= 1 && $annual_incomesCountNum >= 1 && $ordersCountNum >= 1 ){

            Log::info('業種数：' . $industriesCountNum . '、職種数：' . $occupationsCountNum . '、雇用形態数：' . $employment_statusesCountNum . 
                 '、都道府県数：' . $prefecturesCountNum . '、年収数：' . $annual_incomesCountNum . '、並び順数：' . $ordersCountNum);

            return [
                'success' => true,
                'industries' => $industries,
                'occupations' => $occupations,
                'employment_statuses' => $employment_statuses,
                'prefectures' => $prefectures,
                'annual_incomes' => $annual_incomes,
                'orders' => $orders,
            ];
    
        }else{
            return [
                'success' => false,
            ];
        }
    }
}