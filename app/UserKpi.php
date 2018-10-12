<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserKpi extends Eloquent {
    protected $fillable = ['user_id','channel_id'];

    // get list KPI set base on userId
    public static function getKpiUserId($userId, $year, $month) {
        return UserKpi::where('user_id', $userId)
            ->where('kpi.'.$year.'.'.$month, 'exists', true)
            ->get();
    }

    // get KPI set base on user and channel
    public static function getKpiUserChannelId($userId, $channelId) {
        return UserKpi::where('user_id', $userId)
            ->where('channel_id', $channelId)
            ->first();
    }
}