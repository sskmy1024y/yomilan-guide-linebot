<?php

namespace App\Services\Course;

use App\Models\Area;
use Illuminate\Support\Facades\DB;

class CourseGenerate
{
  private $latitude = 0.0;
  private $longitude = 0.0;

  /**
   * コンストラクタの作成
   * 
   * @param float $latitude
   * @param float $longitude
   */
  public function __construct($latitude, $longitude)
  {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
  }

  /**
   * メイン関数
   */
  public function main()
  {
    // TODO: 回れる時間
    $usable_time = 480; // 8時間 * 60分

    $reaming = 0;
    $facilities = [];
    do {
      $_facilities = self::_getFacilitiesEveryArea();
      $_facilities = array_merge($facilities, $_facilities);
      $facilities += self::_sortFacilitiesByDistance($_facilities);
      $orbit_time = self::_getOrbitTime($facilities);
      $reaming += $orbit_time;
    } while ($reaming < $usable_time);

    return $facilities;
  }

  /**
   * エリア毎に施設をピックアップする
   * 
   * @return array
   */
  private function _getFacilitiesEveryArea(): array
  {
    // TODO: 過去にピックアップした施設は除外する
    $areas = Area::all();

    $facilities = [];
    foreach ($areas as $area) {
      $area_facilities = $area->facilies();

      // TODO: 取得条件を変えなければならない
      $facility = $area_facilities->first();

      // nullや飲食施設を除外する
      if ($facility !== null && $facility->isValidAttraction()) {
        $facilities[] = $facility;
      }
    }
    return $facilities;
  }

  /**
   * 施設の配列情報を、現在地からの近さで並べ替える
   * 
   * @param array $facilities 施設の配列
   * @return array
   */
  private function _sortFacilitiesByDistance(array $facilities)
  {
    foreach ($facilities as $key => $value) {
      $sort[$key] = $value->distance($this->latitude, $this->longitude);
    }

    array_multisort($sort, SORT_ASC, $facilities);
    return $facilities;
  }

  /**
   * 周回にかかる時間を算出
   * 
   * @param array $facilities
   * @return int minute
   */
  private function _getOrbitTime(array $facilities)
  {
    $sum = 0;
    foreach ($facilities as $key => $facility) {
      if ($key === 0) {
        [, $time] = self::_intervalTwiceLocations([
          'latitude' => $this->latitude,
          'longitude' => $this->longitude,
        ], [
          'latitude' => $facility->latitude,
          'longitude' => $facility->longitude,
        ]);
      } else {
        [, $time] = self::_intervalTwiceLocations([
          'latitude' => $facilities[$key - 1]->latitude,
          'longitude' => $facilities[$key - 1]->longitude,
        ], [
          'latitude' => $facility->latitude,
          'longitude' => $facility->longitude,
        ]);
      }
      $sum += $time;

      // TODO: 待ち時間を計算して付与。今は固定
      $sum += 15;

      $sum += 5;  // 余白時間
    }

    $sum += $facility->require_time;
    return (int) $sum;
  }

  /**
   * 2つの座標間の距離と移動時間を返す
   * 
   * @param array['latitude','longitude'] $a
   * @param array['latitude','longitude'] $b
   * @return array[distance,time] 距離と時間(min)
   */
  private function _intervalTwiceLocations(array $a, array $b)
  {
    $speed = 75; // (m/min) 平均歩行速度を元に算出

    $distance = 6371 * acos(
      cos(deg2rad($a['latitude']))
        * cos(deg2rad($b['latitude']))
        * cos(deg2rad($b['longitude']) - deg2rad($a['longitude']))
        + sin(deg2rad($a['latitude']))
        * sin(deg2rad($b['latitude']))
    );

    $time = $distance * 1000 / $speed;

    return [$distance, $time];
  }

  /**
   * 指定したロケーションの近辺のアトラクションを取得する
   * 
   * @param float $latitude   緯度
   * @param float $longitude  経度
   * @param int   $distance   検索範囲
   * @param int   $limit      (default: 5)
   * @param array|false       Facilities
   */
  private function _getNearFacilities($latitude, $longitude, $distance, $limit = 5)
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
