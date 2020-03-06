<?php

namespace App\Services\LINEBot;

use App\Models\Visit;
use Illuminate\Support\Facades\Log;
use Util_Assert;

class VisitHelper
{
  /**
   * 指定した日付のVisitを取得する。
   * visitが無ければnullが返る。
   * 
   * @param Group $group
   * @param ExDateTimeImmutable $date
   * @return Visit|null
   */
  public static function sameDayVisit($group, $date)
  {
    return Visit::where('group_id', $group->group_id)->whereBetween('start', $date->DayBetween())->first();
  }

  /**
   * 指定した日付以降で直近のVisitを取得する。
   * visitがなければnullを返す。
   * 
   * @param Group $group
   * @param ExDateTimeImmutable $date
   * @return Visit|null
   */
  public static function afterDayVisit($group, $date)
  {
    return Visit::where('group_id', $group->group_id)->whereDate('start', '>=', $date->Ymd())->orderBy('start')->first();
  }

  /**
   * 指定した日付のVisitを発行。
   * すでに発行済みであれば取得
   * 
   * @param Group $group
   * @param ExDateTimeImmutable $date
   * @return Visit
   */
  public static function insertIgnore($group, $datetime)
  {
    $visit = VisitHelper::sameDayVisit($group, $datetime);
    if ($visit === null) {
      $visit = new Visit;
      $visit->group()->associate($group);
      $visit->start = $datetime;
      $visit->save();
    } else {
      $visit->start = $datetime;
      $visit->save();
    }

    return $visit;
  }
}
