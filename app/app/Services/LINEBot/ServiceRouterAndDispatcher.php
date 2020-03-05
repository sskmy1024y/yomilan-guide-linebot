<?php

namespace App\Services\LINEBot;

use App\Services\LINEBot\Actions\Help_Action;
use App\Services\LINEBot\Actions\Route_Action;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Services\LINEBot\Actions\Visit_Action;
use App\Services\LINEBot\MessageHelper\RouteFlexMessageBuilder;
use App\Services\Watson\Watson_Assistant;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use TalkType;
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
   * @param string $text ユーザーから送られた文字列
   * @return MessageBuilder|null
   */
  public function watsonRouterAndDispatch($text)
  {
    $route_map = [
      TalkType::COURSE_REVIEW => [
        'action' => function () {
          return Route_Action::showCurrentRouteFromEvent($this->event);
        },
      ],
      TalkType::PLANING => [
        'action' => function () {
          $watson_reply = 'よみうりランドに行ってみよう';
          $uri_action = new UriTemplateActionBuilder('コースを作ってみる', 'line://app/1653895916-Q4beDgJp');
          $img_url = secure_asset('assets/img/route_img.jpg');
          $button = new ButtonTemplateBuilder($watson_reply, 'いつも以上によみうりランドを楽しめるコースが作れます', $img_url, [$uri_action]);
          return new TemplateMessageBuilder($watson_reply, $button);
        },
      ],
      // TODO: イベント関連

      TalkType::HELP => [
        'action' => function () {
          return Help_Action::main();
        }
      ]
    ];

    $watson_assistant = new Watson_Assistant($text);
    $talk_type = $watson_assistant->topIntents();

    if ($talk_type !== null && array_key_exists($talk_type, $route_map)) {
      Util_Assert::keyExists($route_map[$talk_type], 'action');
      return $route_map[$talk_type]['action']();
    }

    return new TextMessageBuilder('別の言葉で言い直して');
  }


  /**
   * @param string $group_id
   * @return MessageBuilder|null
   */
  public static function staticDispatch($group_id, $datetime)
  {
    $route = Visit_Action::initializeVisit($group_id, $datetime);
    return new RouteFlexMessageBuilder($route);
  }
}
