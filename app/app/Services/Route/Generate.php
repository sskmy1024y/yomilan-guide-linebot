<?php

namespace App\Services\Course;

use App\Models\Facility;
use App\Models\Route;
use Illuminate\Support\Facades\DB;

class CourseGenerate
{
  private $current; // Current Location

  /**
   * コンストラクタの作成
   * 
   * @param array $current current location
   */
  public function __construct(array $current)
  {
    $this->current = $current;
  }

  /**
   * 指定したロケーションの近辺のアトラクションを取得する
   * 
   * @param float $latitude
   * @param float $longitude
   * @param int $distance
   * @param int $limit (default: 5)
   * @param array Facilities
   */
  public function generate($latitude, $longitude, $distance, $limit = 5)
  {

    $sql = "SELECT
            id, name, latitude, longitude,
            (
                6371 * acos(
                    cos(radians(:latitude1))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians(:longitude))
                    + sin(radians(:latitude2))
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
      'latitude1' => $latitude,
      'latitude2' => $latitude,
      'longitude' => $longitude,
      'distance' => $distance,
      'limit' => $limit,
    ]);
  }
}
