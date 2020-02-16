<?php

namespace App\Services\LINEBot;

use App\Models\Visit;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Util_DateTime;

/**
 * 入園に関するヘルパクラス
 */
class Visit_Helper
{
  /**
   * 入園に関する初期化を行う。
   * ルート生成等
   * 
   * @param BaseEvent $event
   * @return MessageBuilder
   */
  public static function initializeVisit($event)
  {
    $now = Util_DateTime::createNow();
    $group_id = GroupHelper::identify($event)->group_id;

    $visit = self::_sameDayVisit($group_id, $now);
    if ($visit === null) {
      $visit = Visit::create([
        'group_id' => $group_id,
      ]);
    }

    return new TextMessageBuilder($visit->id);
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
