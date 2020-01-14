<?php

// php artisan make:model Facility --migration

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public function congestion()
    {
        return $this->hasMany('Models/App/Congestion');
    }

    /**
     * 最新の混雑状況を取得する
     */
    public function last_congestion()
    {
        return $this->congestion->last();
    }
}
