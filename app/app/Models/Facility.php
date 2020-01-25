<?php

// php artisan make:model Facility --migration

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Facility extends Model
{
    public function congestion()
    {
        return $this->hasMany('App\Models\Congestion');
    }

    /**
     * 最新の混雑状況を取得する
     */
    public function last_congestion()
    {
        return $this->congestion->last();
    }

    public function area()
    {
        return $this->hasOne('App\Models\Area');
    }
}
