<?php

namespace App\Services\Course;

use App\Models\Area;
use Illuminate\Support\Facades\DB;
use Util_Assert;
use Util_DateTime;

class CourseGenerate
{
  private $start_time;
  private $end_time;
  private $latitude;    // 現在位置（緯度）
  private $longitude;   // 現在位置（経度）
  private $facilities = [];   // 候補リスト

  /**
   * コンストラクタの作成
   * 
   * @param float $latitude
   * @param float $longitude
   */
  public function __construct($latitude = 0.0, $longitude = 0.0)
  {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
    $this->start_time = Util_DateTime::createNow();
    $this->end_time = Util_DateTime::createFromHis('18:00:00');
  }

  /**
   * 現在地を設定
   * 
   * @param float $latitude
   * @param float $longitude
   */
  public function setCurrentLocation($latitude, $longitude)
  {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
  }

  /**
   * 開始時間を設定
   * @param datetime $start_time
   */
  public function setStartTime($start_time)
  {
    $this->start_time = $start_time;
  }

  /**
   * 終了時間を設定
   * @param datetime $start_time
   */
  public function setEndTime($end_time)
  {
    $this->end_time = $end_time;
  }

  /**
   * メイン関数
   */
  public function main()
  {
    $usable_time = $this->start_time->diffInMinutes($this->end_time);

    if ($this->start_time->hour < 13) {
      $lanch_time = 60;
    } else {
      $lanch_time = 0;
    }

    // 遊べる時間を自動計算
    $active_time = $usable_time - $lanch_time; // 8時間 * 60分 - 60分 お昼時間を除く

    $reaming = 0;
    do {
      $ids = array_column($this->facilities, "id");
      // エリアごとの施設を取得して、候補一覧とマージ 
      $_facilities = array_merge(self::_getFacilitiesEveryArea($ids), $this->facilities);
      // 施設同士の近さで並び替える
      $_facilities = self::_sortByDistanceFromFacilities($_facilities);

      // 施設を回るのにかかる時間を計算
      $orbit_time = self::_getOrbitTime($_facilities);

      // 直前の時間と同じならこれ以上施設がない = 検索終了
      if ($orbit_time == $reaming || $orbit_time > $active_time) {
        break;
      }

      // 値を更新
      $this->facilities = $_facilities;
      $reaming = $orbit_time;
    } while ($reaming < $active_time);


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
    Util_Assert::notEmpty($_facilities);

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
    Util_Assert::notEmpty($facilities);

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
   * お昼ご飯の場所を、時間的に適切な順に挿入する
   * 
   * @param array $lanch お昼ご飯の場所
   * @return array 
   */
  private function _mergeLanchFacility($lanch)
  {
    return [];
  }
}
