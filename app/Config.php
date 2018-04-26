<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Config extends Eloquent
{
    public static function getByKey($key){
        $config = Config::where('key', $key)
            ->where('active', 1)
            ->select('value')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($config) {
            return $config->value;
        } else {
            return "";
        }
    }


    public static function getByKeys($key){
        $config = Config::whereIn('key', $key)
            ->where('active', 1)
            ->select('key', 'value')
            ->orderBy('created_at', 'desc')
            ->get();
        $res = array();

        if ($config) {
            foreach ($config as $item){
                $key    = $item->key;
                $value  = $item->value;
                if(!isset($res[$key])){
                    $res[$key] = $value;
                }
            }
        }
        return $res;
    }
}
