<?php

namespace App\Services\LINEBot;

use App\Models\Visit;
use Illuminate\Support\Facades\Log;
use Util_Assert;

class VisitHelper
{
  /**
   * 指定した日付で発行されたVisitを取得する。
   * visitが無ければnullが返る。
   * 
   * @param Group $group
   * @param ExDateTimeImmutable $date
   * @return Visit|null
   */
  public static function sameDayVisit($group, $date)
  {
    return Visit::where('group_id', $group->id)->whereBetween('created_at', $date->DayBetween())->latest()->first();
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
