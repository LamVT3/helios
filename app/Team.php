<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Team extends Eloquent
{

    public function getMemberIdsArrayAttribute()
    {
        if (!count($this->members)) return [];

        $members = [];
        foreach ($this->members as $m){
            $members[] = $m['user_id'];
        }
        return $members;
    }

    public function getSourceIdsArrayAttribute()
    {
        if (!count($this->sources)) return [];

        return array_keys($this->sources);
    }
}
