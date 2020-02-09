<?php

namespace App\Services\Course;

use App\Models\Area;
use Illuminate\Support\Facades\DB;
use Util_Assert;

class CourseGenerate
{
  private $latitude = 0.0;    // 現在位置（緯度）
  private $longitude = 0.0;   // 現在位置（経度）
  private $facilities = [];   // 候補リスト

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
    // TODO: 回れる時間を自動計算
    $usable_time = 480; // 8時間 * 60分

    $reaming = 0;
    do {
      $ids = array_column($this->facilities, "id");
      // エリアごとの施設を取得して、候補一覧とマージ 
      $_facilities = array_merge(self::_getFacilitiesEveryArea($ids), $this->facilities);
      // 施設同士の近さで並び替える
      // TODO: global関数に入れるのは、計算時間がusable_timeを下回った場合のみにする
      $_facilities = self::_sortByDistanceFromFacilities($_facilities);

      // 施設を回るのにかかる時間を計算
      $orbit_time = self::_getOrbitTime($_facilities);

      // 直前の時間と同じならこれ以上施設がない = 検索終了
      if ($orbit_time == $reaming || $orbit_time > $usable_time) {
        break;
      }

      // 値を更新
      $this->facilities = $_facilities;
      $reaming = $orbit_time;
    } while ($reaming < $usable_time);


    return [
      'use_time' => $reaming,
      'facilities' => $this->facilities,
    ];
  }

  /**
   * エリア毎に施設をピックアップする
   * 
   * @param array $exclude_ids 除外する施設のID
   * @return array
   */
  private function _getFacilitiesEveryArea(array $exclude_ids = []): array
  {
    $areas = Area::all();

    $facilities = [];
    foreach ($areas as $area) {
      $area_facilities = $area->facilies()->whereNotIn("id", $exclude_ids);  // 同じアトラクションを排除

      // TODO: 取得条件をユーザによって変える（子連れ、年代等）
      // $area_facilities = $area_facilities->where("for_child", "=", 0);

      $facility = $area_facilities->first();

      // nullや飲食施設を除外する
      if ($facility !== null && $facility->isValidAttraction()) {
        $facilities[] = $facility;
      }
    }
    return $facilities;
  }

  /**
   * 施設の近さに基づいてリストを並び替えて返す
   * 
   * @param array $_facilities 
   * @return array
   */
  private function _sortByDistanceFromFacilities(array $_facilities)
  {
    $next = self::_sortByDistanceFromCurrentLocation($_facilities);
    $facilities = [];
    do {
      $facilities = array_merge($facilities, $next);
      if (($key = array_search($next, $_facilities)) !== false) {
        unset($_facilities[$key]);
        $next = $next->getNearFacility($_facilities);
      } else {
        break;
      }
    } while (count($_facilities) > 0);

    return $facilities;
  }

  /**
   * 施設の配列情報を、現在地からの近さで並べ替えて返す
   * 
   * @param array $facilities 施設の配列
   * @return array
   */
  private function _sortByDistanceFromCurrentLocation(array $facilities): array
  {
    Util_Assert::notEmpty($facilities);
    foreach ($facilities as $key => $facility) {
      $sort[$key] = $facility->distance($this->latitude, $this->longitude);
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
  private function _getOrbitTime(array $facilities): int
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
      if ($time > 1000) {
        var_dump($facility);
      }

      $sum += $facility->require_time;

      // TODO: 待ち時間を計算して付与。今は固定
      $sum += 15;

      $sum += 5;  // 余白時間
    }


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
  private function _getNearFacilitiesFromCurrentLocation($latitude, $longitude, $distance, $limit = 5)
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
