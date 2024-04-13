<?php
namespace App\Traits;

use App\Company;
use App\Job;
use App\User;

trait ModelDeleteTrait
{
    public static function userModelDelete()
    {
        // ユーザーIDを配列にして降順に並べ替え
        $userIds = User::pluck('id')->toArray();
        sort($userIds);

        // 残したいユーザーIDを配列から削除
        array_splice($userIds, 0, 10);
        $userStartNum = array_shift($userIds);
        
        // このテストで作成した全ユーザー削除
        User::where('id', '>=', $userStartNum)->delete();
    }

    public static function companyModelDelete()
    {
        // ユーザーIDを配列にして降順に並べ替え
        $companyIds = Company::pluck('id')->toArray();
        sort($companyIds);

        // 残したいユーザーIDを配列から削除
        array_splice($companyIds, 0, 10);
        $companyStartNum = array_shift($companyIds);
        
        // このテストで作成した全ユーザー削除
        Company::where('id', '>=', $companyStartNum)->delete();
    }

    public static function jobModelDelete()
    {
        // 求人IDを配列にして降順に並べ替え
        $jobIds = Job::pluck('id')->toArray();
        sort($jobIds);

        // 残したい求人IDを配列から削除
        array_splice($jobIds, 0, 150);
        $jobStartNum = array_shift($jobIds);
        
        // このテストで作成した全求人削除
        Job::where('id', '>=', $jobStartNum)->delete();
    }
}