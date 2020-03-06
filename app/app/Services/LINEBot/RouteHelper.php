<?php

namespace App\Services\LINEBot;

use App\Models\Facility;
use App\Models\Location;
use App\Models\Route;
use App\Models\Visit;
use App\Services\Route\Route_Generate;
use Illuminate\Support\Facades\Log;
use Util_Assert;
use Util_DateTime;
use Util_Validate;

class RouteHelper
{
  /**
   * ルートを生成して登録する
   * 
   * @param int $visit_id 紐づけるための入場ID
   * @param int[] $want_facilities_ids 周回希望のアトラクションリスト
   * @return Route
   */
  public static function makeRoute($visit_id, $want_facilities_ids = [])
  {
    $visit = Visit::find($visit_id);
    Util_Assert::notNull($visit);

    $start_time = Util_DateTime::createFromYmdHis($visit->start);

    $gen = new Route_Generate($start_time);

    $route = new Route;
    $route->visit_id = $visit_id;
    $route->save();

    $group = $visit->group;

    // 行きたいリストを登録
    if (count($want_facilities_ids) > 0) {
      $_want_facilities = Facility::whereIn('id', $want_facilities_ids)->get();
      $route->want_facilities()->attach($_want_facilities);
      $route->save();
      foreach ($route->want_facilities as $want_facility) {
        $gen->setFacility($want_facility);
      }

      // 行きたいリストから子供を持つ割合を生成
      if (count($want_facilities_ids) >= 3) {  // 一定数以上選択していない場合は考慮しない
        $percentage = self::_calcHasChildPercentage($want_facilities_ids);
        GroupHelper::saveHasChildProbability($group, $percentage);
      }
    }

    $for_child_facilities = self::_getFacilitiesForChildByHasPercent($group->has_child_precent);
    foreach ($for_child_facilities as $child_facility) {
      $gen->setFacility($child_facility);
    }

    $gen_route = $gen->make();
    $props = [];
    foreach ($gen_route['facilities'] as $index => $facility) {
      $props[$facility->id] = [
        'index' => $index,
      ];
    }
    $route->facilities()->attach($props);
    $route->save();

    return $route;
  }

  /**
   * 周回にかかる時間を算出する。
   * 
   * @param Facility[] $facilities 周回する施設リスト
   * @param Location|null $start 出発点のLocation。nullの場合、facility[0]を起点にする。
   * @return int 移動時間(min)
   */
  public static function orbitTime(array $facilities, $start = null)
  {
    Util_Assert::notEmpty($facilities);
    if ($start === null && count($facilities) < 2) {
      throw new \Exception_AssertionFailed("the array must have over than two facilities.");
    }

    $sum_time = 0;
    ksort($facilities);
    foreach ($facilities as $key => $facility) {
      if ($key == 0) {
        if ($start === null) continue;
        list($move_time, $play_time) = RouteHelper::travelAndOrbit($facility, $start);
      } else {
        list($move_time, $play_time) = RouteHelper::travelAndOrbit($facility, $facilities[$key - 1]->location());
      }
      // 移動時間と、アトラクションの所要時間を追加
      $sum_time += $move_time + $play_time;
    }

    return $sum_time;
  }

  /** 
   * 施設までの移動時間及び到着から終了までにかかる時間を計算して返す
   * 
   * @param Facility $facility ターゲットの施設
   * @param Location|null $departure 出発点のLocation。nullの場合、facility[0]を起点にする。
   * @return int[]  [ 移動時間(min), 到着から終了までの時間(min) ]
   */
  public static function travelAndOrbit($facility, $departure = null)
  {
    if ($departure === null) {
      $departure = Location::enterance();
    }
    $move_time = $departure->travelTime($facility->location());
    $play_time = $facility->require_time ?? 15;

    // TODO: 待ち時間を計算して付与。今は固定
    $play_time += 5;
    // Padding Time
    $play_time += 5;

    return array(round($move_time), round($play_time));
  }

  /**
   * VisitIDから生成ずみのルートを取得。無ければfalse
   * 
   * @param int $visit_id
   * @return Route|false
   */
  public static function latest($visit_id)
  {
    Util_Assert::positiveInt($visit_id);
    $route = Route::where('visit_id', $visit_id)->latest()->first();
    return $route === null ? false : $route;
  }

  /**
   * 施設IDの配列から子供がいる割合を計算する
   * 
   * @param int[] $facility_ids
   * @return float
   */
  private static function _calcHasChildPercentage(array $facility_ids)
  {
    Util_Assert::intSequentialArray($facility_ids);
    $want_facilities = Facility::whereIn('id', $facility_ids)->get();

    $count = 0;
    foreach ($want_facilities as $facility) {
      if ($facility->for_child) {
        $count += 1;
      }
    }
    return $count / count($facility_ids);
  }

  /**
   * 子供を持っている確率を基に、予め施設リストに子供向けアトラクションリストを取得する
   * 
   * @param float $percent
   * @return Eloquent
   */
  private static function _getFacilitiesForChildByHasPercent($percent)
  {
    // TODO: 現状は(確率/10)個の施設を取得している
    return Facility::where('for_child', true)->inRandomOrder()->limit(floor($percent))->get();
  }
}
