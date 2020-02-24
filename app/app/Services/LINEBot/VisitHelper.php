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
    return Visit::where('group_id', $group_id)->whereBetween('created_at', $date->DayBetween())->first();
  }
}
