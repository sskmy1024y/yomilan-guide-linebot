<?php

namespace App\Services\LINEBot;

use App\Models\Route;
use App\Models\Visit;
use App\Services\Route\Route_Generate;
use Util_Assert;
use Util_DateTime;

class RouteHelper
{
  /**
   * ルートを生成して登録する
   * 
   * @param int $visit_id 紐づけるための入場ID
   * @return Route
   */
  public static function makeRoute($visit_id)
  {
    $visit = Visit::find($visit_id);
    Util_Assert::notNull($visit);

    $start_time = Util_DateTime::createFromYmdHis($visit->start);

    $gen = new Route_Generate($start_time);
    $gen_route = $gen->make();

    $props = [];
    foreach ($gen_route['facilities'] as $index => $facility) {
      $props[$facility->id] = [
        'index' => $index,
      ];
    }

    $route = new Route;
    $route->visit_id = $visit_id;
    $route->save();
    $route->facilities()->attach($props);
    $route->save();

    return $route;
  }

  /**
   * 周回にかかる時間を算出する。
   * 
   * @param Facility[] $facilities 周回する施設リスト
   * @param Location|null $start 出発点のLocation。nullの場合、facility[0]を起点にする。
   * @param int 移動時間(min)
   */
  public static function orbitTime(array $facilities, $start = null)
  {
    Util_Assert::notEmpty($facilities);
    if ($start === null && count($facilities) < 2) {
      throw new \Exception_AssertionFailed("the array must have over than two facilities.");
    }

    $sum_time = 0;
    foreach ($facilities as $key => $facility) {
      if ($key === 0) {
        if ($start === null) continue;
        $time = $start->travelTime($facility->location());
      } else {
        $time = $facilities[$key - 1]->location()->travelTime($facility->location());
      }
      // 移動時間と、アトラクションの所要時間を追加
      $sum_time += $time;
      $sum_time += $facility->require_time;

      // TODO: 待ち時間を計算して付与。今は固定
      $sum_time += 5;
      // Padding Time
      $sum_time += 5;
    }

    return (int) $sum_time;
  }
}
