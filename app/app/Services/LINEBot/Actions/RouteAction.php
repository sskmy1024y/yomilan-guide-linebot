<?php

namespace App\Services\LINEBot\Actions;

use App\Services\LINEBot\GroupHelper;
use App\Services\LINEBot\MessageHelper\RouteFlexMessageBuilder;
use App\Services\LINEBot\RouteHelper;
use App\Services\LINEBot\VisitHelper;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Util_Assert;
use Util_DateTime;

class Route_Action
{
  /**
   * 直近に生成されたルートを取得する
   * 
   * @param string $group_id
   * @param ExDateTimeImmutable $date
   * @return Route|false
   */
  public static function showCurrentRoute($group_id, $date)
  {
    Util_Assert::nonEmptyString($group_id);

    $visit = VisitHelper::sameDayVisit($group_id, $date);
    if ($visit === null) {
      return false;
    }

    return RouteHelper::latest($visit->id);
  }

  public static function showCurrentRouteFromEvent($event)
  {
    $group_id = GroupHelper::identifyFromEvent($event)->group_id;
    $date = Util_DateTime::createNow();
    $route = Route_Action::showCurrentRoute($group_id, $date);

    if ($route === false) {
      return new TextMessageBuilder("まだルートがないよ");
    } else {
      return new RouteFlexMessageBuilder($route);
    }
  }
}
