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

    public function near_facilities()
    {
        $sql = "SELECT
            id, name, latitude, longitude,
            (
                6371 * acos(
                    cos(radians(35.6804067))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians(139.7550152))
                    + sin(radians(35.6804067))
                    * sin(radians(latitude))
                )
            ) AS distance
        FROM
            facilities
        HAVING
            distance <= :distance
        ORDER BY
            distance
        LIMIT :limit
        ;";

        return DB::select($sql, [
            ':distance' => 3,
            ':limit' => 10,
        ]);
    }
}
