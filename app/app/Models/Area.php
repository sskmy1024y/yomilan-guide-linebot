<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function facilies()
    {
        return $this->hasMany('App\Models\Facility');
    }
}
