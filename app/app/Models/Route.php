<?php

namespace Models\App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public function visit()
    {
        return $this->belongsTo('Models\App\Visit');
    }

    public function group_id()
    {
        return $this->visit->group_id;
    }

    public function facilities()
    {
        return $this->belongsToMany('Models\App\Facility');
    }
}
