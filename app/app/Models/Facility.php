<?php

// php artisan make:model Facility --migration

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Facility extends Model
{
    protected $casts = [
        'use_pass' => 'boolean',
        'for_child' => 'boolean',
        'is_indoor' => 'boolean',
        'enable' => 'boolean',
    ];

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

    /**
     * 有効なアトラクション施設かどうかを判定
     * @return boolean
     */
    public function isValidAttraction()
    {
        return $this->type === FacilityType::ATTRACTION && $this->enable === true;
    }

    /**
     * 指定した座標との直線距離を返す
     * 
     * @param float $latitude 緯度
     * @param float $longitude 経度
     * @return float 距離(km)
     */
    public function distance($latitude, $longitude)
    {
        return 6371 * acos(
            cos(deg2rad($latitude))
                * cos(deg2rad($this->latitude))
                * cos(deg2rad($this->longitude) - deg2rad($longitude))
                + sin(deg2rad($latitude))
                * sin(deg2rad($this->latitude))
        );
    }
}
