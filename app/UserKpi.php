<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserKpi extends Eloquent {
    protected $fillable = ['user_id','channel_id'];

    // get list KPI set base on userId
    public static function getKpiOneParam($userId) {
        return UserKpi::where('user_id', $userId)
            ->get();
    }

    // get KPI set base on user and channel
    public static function getKpiTwoParam($userId, $channelId) {
        return UserKpi::where('user_id', $userId)
            ->where('channel_id', $channelId)
            ->first();
    }
}