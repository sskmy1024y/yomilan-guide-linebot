<?php

namespace App\Services\LINEBot\Actions;

use App\Models\Route;
use App\Models\Visit;
use App\Services\LINEBot\GroupHelper;
use App\Services\LINEBot\MessageHelper\RouteFlexMessage;
use App\Services\LINEBot\RouteHelper;
use Util_DateTime;

/**
 * 入園に関するアクションクラス
 * 
 * NOTE: 流れはこんな感じ
 * 1. 「よみらん行きたい」→ LIFFを出して、回りたい場所と入園日時を設定
 *    Visitアクションを実行して、入園に関しての初期設定を行う
 * 2. RouteHelperでルートを生成して、LINEで返す
 *    初期設定済みの時は、「ルート生成済みだけど変更する？」
 */
class Visit_Action
{
  /**
   * 入園に関する初期化を行い、ルート生成をする
   * 
   * @param int $group_id グループID
   * @return Route
   */
  public static function initializeVisit($group_id)
  {
    $start = Util_DateTime::createFromHis('10:00:00');  // TODO: 開始日時はLIFFから取得する

    $visit = self::_sameDayVisit($group_id, $start);
    if ($visit === null) {
      $visit = Visit::create([
        'group_id' => $group_id,
        'start' => $start,
      ]);
    }

    $route = self::_latestRoute($visit->id);
    if ($route === false) {
      $route = RouteHelper::makeRoute($visit->id);
    }
    return $route;
  }

  /**
   * LINEのイベントを元に入園に関する初期化を行い、ルート生成をする
   * 
   * @param BaseEvent $event
   * @return MessageBuilder 応答メッセージ
   */
  public static function initVisitFromEvent($event)
  {
    $group_id = GroupHelper::identifyFromEvent($event)->group_id;
    $route = Visit_Action::initializeVisit($group_id);

    return new RouteFlexMessage($route);
  }

  /**
   * 生成ずみのルートを取得。無ければfalse
   * 
   * @param int $visit_id
   * @return Route|false
   */
  private static function _latestRoute($visit_id)
  {
    $route = Route::where('visit_id', $visit_id)->latest()->first();
    return $route === null ? false : $route;
  }

  /**
   * 同じ日にすでにvisitが発行されているかどうかを確認する
   * 
   * @param int $group_id
   * @param ExDateTimeImmutable $date
   * @return Visit|null
   */
  private static function _sameDayVisit($group_id, $date)
  {
    return Visit::where('group_id', $group_id)->whereBetween('created_at', $date->DayBetween())->first();
  }
}
