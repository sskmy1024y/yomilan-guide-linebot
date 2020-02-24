<?php

namespace App\Services\LINEBot;

use App\Models\Group;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Event\JoinEvent;
use Util_Assert;

class GroupHelper
{
  /**
   * グループ情報を取得する。登録されていない場合は、追加する
   * 
   * @param string $group_id
   * @return Group
   */
  public static function identify($group_id)
  {
    Util_Assert::nonEmptyString($group_id);

    $group = Group::where('group_id', $group_id)->first();

    if ($group === null) {
      $group = Group::create([
        'group_id' => $group_id,
      ]);
    }
    return $group;
  }

  /**
   * LINEのイベント情報を元に、グループ情報を取得する。登録されていない場合、追加する
   * 
   * @param JoinEvent $event join event
   * @return Group
   */
  public static function identifyFromEvent($event)
  {
    $sourse_id = $event->getEventSourceId();
    if ($event->getReplyToken() === "dummyToken") {
      $sourse_id = "dummyId";
    }
    return GroupHelper::identify($sourse_id);
  }
}
