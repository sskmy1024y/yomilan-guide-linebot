<?php

namespace App\Services\LINEBot\Actions;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class Help_Action
{
  /**
   * ヘルプのメッセージを表示する。
   */
  public static function main()
  {
    $list = [
      "【このBOTの使い方】\n",
      "「遊びに行きたい」\n→ よみうりランドのプランで遊ぶプランを作成します\n\n",
      "「コースを見たい」\n→ 作ったコースが見られます",
    ];

    $message = "";
    foreach ($list as $item) {
      $message .= $item;
    }

    return new TextMessageBuilder($message);
  }
}
