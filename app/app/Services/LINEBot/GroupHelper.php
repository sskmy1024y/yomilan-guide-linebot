<?php

namespace App\Services\LINEBot;

use App\Models\Group;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Event\JoinEvent;
use Util_Assert;

class GroupHelper
{
  /**
   * グループIDが登録されているかどうかチェックする
   * 
   * @param JoinEvent $event join event
   * @return Group
   */
  public static function identify($event)
  {
    $sourse_id = $event->getEventSourceId();
    if ($event->getReplyToken() === "dummyToken") {
      $sourse_id = "dummyId";
    }
    Util_Assert::nonEmptyString($sourse_id);

    $group = Group::where('group_id', $sourse_id)->first();

    if ($group === null) {
      $group = Group::create([
        'group_id' => $sourse_id,
      ]);
    }
    return $group;
  }
}
