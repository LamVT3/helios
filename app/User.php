<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Eloquent implements AuthenticatableContract
{
    use Authenticatable;
    protected $connection = 'mongodb';
    protected $primaryKey = '_id';
    // protected $dates = ['birthday', 'entry.date'];
    protected static $unguarded = true;

    // get user was activated with role is Marketer
    public static function getMarketerActive() {
        return User::where('role', 'Marketer')
            ->where('is_active', 1)
            ->get();
    }
}
