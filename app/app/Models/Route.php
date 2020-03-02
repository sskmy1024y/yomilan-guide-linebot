<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public function visit()
    {
        return $this->belongsTo('App\Models\Visit');
    }

    public function group_id()
    {
        return $this->visit->group_id;
    }

    public function want_facilities()
    {
        return $this->hasMany('App\Models\WantFacility');
    }

    public function facilities()
    {
        return $this->belongsToMany('App\Models\Facility', 'route_facility', 'route_id', 'facility_id')->withPivot('index');
    }
}
