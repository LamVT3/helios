<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class KPIUser extends Eloquent
{
    public static function find($userId, $year, $month){
        return KPIUser::where('userId', $userId)
            ->where('date', 'like', '%'.$year.'-'.$month.'%')
            ->select('date', 'kpiC3b')
            ->get();
    }

    public static function checkExists($userId, $year, $month, $day){
        return KPIUser::where('userId', $userId)
            ->where('date', $year.'-'.$month.'-'.$day)
            ->get();
    }
}
