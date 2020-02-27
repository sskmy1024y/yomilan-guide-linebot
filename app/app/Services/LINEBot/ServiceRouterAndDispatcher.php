<?php

namespace App\Services\LINEBot;

use App\Services\LINEBot\Actions\Route_Action;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Services\LINEBot\Actions\Visit_Action;
use App\Services\LINEBot\MessageHelper\RouteFlexMessageBuilder;
use Util_Assert;

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
      'ルートを見たい' => [
        'action' => function () {
          return Route_Action::showCurrentRouteFromEvent($this->event);
        },
      ],
      'ルートを生成して' => [
        'action' => function () {
          // return Visit_Action::initVisitFromEvent($this->event);
          return new TextMessageBuilder('line://app/1653895916-Q4beDgJp');
        },
      ],
      'よみうりランド行きたい' => [
        'action' => function () {
          return new TextMessageBuilder('TODO : LIFFを開く');
        }
      ]
    ];

    if (array_key_exists($text, $route_map)) {
      Util_Assert::keyExists($route_map[$text], 'action');
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


  /**
   * @param string $group_id
   * @return MessageBuilder|null
   */
  public static function staticDispatch($group_id)
  {
    $route = Visit_Action::initializeVisit($group_id);
    return new RouteFlexMessageBuilder($route);
  }
}
