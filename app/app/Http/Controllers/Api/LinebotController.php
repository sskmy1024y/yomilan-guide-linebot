<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LINEBot\Actions\Visit_Action;
use App\Services\LINEBot\MessageHelper\RouteFlexMessageBuilder;
use App\Services\LINEBot\ServiceRouterAndDispatcher;
use App\Services\LINEBot\VisitHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\JoinEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Util_DateTime;

class LinebotController extends Controller
{
  public function callback(Request $request)
  {
    // LINEからのリクエスト情報を取得
    $channel_secret = env('LINE_CHANNEL_SECRET');
    $access_token = env('LINE_ACCESS_TOKEN');
    $request_body = $request->getContent();
    $signature = $request->header(HTTPHeader::LINE_SIGNATURE);

    // LINEからの送信を検証
    if (!SignatureValidator::validateSignature($request_body, $channel_secret, $signature)) {
      logger()->info('recieved from difference line-server');
      abort(400);
    }

    $client = new CurlHTTPClient($access_token);
    $bot = new LINEBot($client, ['channelSecret' => $channel_secret]);

    try {
      $events = $bot->parseEventRequest($request_body, $signature);

      foreach ($events as $event) {
        // デバッグ用のDummyTokenチェック
        if ($event->getReplyToken() === 'dummyToken') {
          $bot = new LINEBot($client, [
            'channelSecret' => $channel_secret,
            'endpointBase' => env('LINE_ENDPOINT_BASE', LINEBot::DEFAULT_ENDPOINT_BASE),
          ]);
        }

        // ここからが分岐 ===================================
        if ($event instanceof FollowEvent) {
          $reply_token = $event->getReplyToken();
          $reply = new TextMessageBuilder("友達登録ありがとう！使い方を知りたい時は「ヘルプ」って言ってね。\n\nこのBOTはグループでも使えるよ。ぜひグループにも招待してね！");
          $bot->replyMessage($reply_token, $reply);
        } else if ($event instanceof JoinEvent) {
          $reply_token = $event->getReplyToken();
          $bot->replyText($reply_token, "グループに追加してくれてありがとう！");
        } else if ($event instanceof MessageEvent && $event instanceof TextMessage) {   // テキストメッセージの場合
          $text = $event->getText();              // LINEで送信されたテキスト
          $reply_token = $event->getReplyToken(); // 返信用トークン

          $service = new ServiceRouterAndDispatcher($event);
          $reply = $service->watsonRouterAndDispatch($text);

          if ($reply !== null) {
            $bot->replyMessage($reply_token, $reply);
          }
        }
      }
    } catch (\Exception $e) {
      Log::error($e);
    }
  }

  public function postback(Request $request)
  {
    $channel_secret = env('LINE_CHANNEL_SECRET');
    $access_token = env('LINE_ACCESS_TOKEN');

    $httpClient = new CurlHTTPClient($access_token);
    $bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

    $group_id = $request->input('groupId');
    $_datetime = $request->input('datetime');
    $selected_ids = $request->input('selectedIds');

    $datetime = Util_DateTime::createFromYmdHis($_datetime);
    $route = Visit_Action::initializeVisit($group_id, $datetime, $selected_ids);

    $message = new RouteFlexMessageBuilder($route);

    if ($message !== null) {
      $response = $bot->pushMessage($group_id, $message);
      Log::info($response->getHTTPStatus() . ' ' . $response->getRawBody());
    }
  }
}
