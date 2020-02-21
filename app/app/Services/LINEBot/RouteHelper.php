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
}
