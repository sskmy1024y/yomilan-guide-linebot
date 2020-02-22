<?php

// php artisan make:model Facility --migration

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Util_Assert;

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
     * 座標情報をLocationクラスで取得
     * @return Location
     */
    public function location()
    {
        return new Location($this->latitude, $this->longitude);
    }

    /**
     * 指定したLocationとの直線距離を返す
     * 
     * @param Location $location
     * @return float 距離(km)
     */
    public function distance($location)
    {
        return $this->location()->distance($location);
    }

    /**
     * 指定したリストから、この施設に一番近い施設を返す
     * 
     * @param array $facilities 
     * @return array|null
     */
    public function getMostNearFacility(array $facilities)
    {
        Util_Assert::notEmpty($facilities);

        $near = [
            'distance' => 0.0,
            'facility' => null,
        ];
        foreach ($facilities as $facility) {
            $distance = $this->distance($facility->location());
            if ($distance > $near['distance']) {
                $near = [
                    'distance' => $distance,
                    'facility' => $facility,
                ];
            }
        }
        return $near['facility'];
    }
}
