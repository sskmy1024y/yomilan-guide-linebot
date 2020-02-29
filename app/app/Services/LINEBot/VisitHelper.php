<?php

namespace App\Services\LINEBot;

use App\Models\Visit;
use Util_Assert;

class VisitHelper
{
  /**
   * 指定した日付で発行されたVisitを取得する。
   * visitが無ければnullが返る。
   * 
   * @param string $group_id
   * @param ExDateTimeImmutable $date
   * @return Visit|null
   */
  public static function sameDayVisit($group_id, $date)
  {
    Util_Assert::nonEmptyString($group_id);
    return Visit::where('group_id', $group_id)->whereBetween('created_at', $date->DayBetween())->latest()->first();
  }

  /**
   * 指定した日付のVisitを発行。
   * すでに発行済みであれば取得
   * 
   * @param string $group_id
   * @param ExDateTimeImmutable $date
   * @return Visit
   */
  public static function insertIgnore($group_id, $datetime)
  {
    $visit = VisitHelper::sameDayVisit($group_id, $datetime);
    if ($visit === null) {
      $visit = Visit::create([
        'group_id' => $group_id,
        'start' => $datetime,
      ]);
    }

    return $visit;
  }
}
