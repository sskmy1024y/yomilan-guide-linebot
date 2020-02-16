<?php

namespace App\Services\LINEBot;

use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class ServiceRouterAndDispatcher
{
  protected $event;

  /**
   * @param BaseEvent
   */
  public function __construct($event)
  {
    $this->event = $event;
  }

  /**
   * stringのテキストによって処理を分岐させる
   * 
   * @deprecated `watsonRouterAndDispatch`を使って分岐させる
   * 
   * @param string $text
   * @return MessageBuilder|null
   */
  public function textRouterAndDispatch($text)
  {
    $route_map = [
      'ルートを調べる' => [
        'action' => function () {
          return Visit_Helper::initializeVisit($this->event);
        },
      ],
    ];

    if (array_key_exists($text, $route_map)) {
      return $route_map[$text]['action']();
    }
    return new TextMessageBuilder('別の言葉で言い直して');
  }

  /**
   * watsonの応答によって処理を分岐させる
   * 
   * @param string $res TODO: ちゃんとWatsonの型を使う
   * @param 
   * @return MessageBuilder|null
   */
  // public function watsonRouterAndDispatch($res)
  // {
  //   return new TextMessageBuilder($res);
  // }
}
