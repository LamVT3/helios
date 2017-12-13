<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MyModel extends Eloquent {

    protected $connection = 'mongodb';

    protected $collection = 'users';

    protected $fillable = [
        'name', 'email', 'password'
    ];
}
