<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LINEBot\GroupHelper;
use App\Services\LINEBot\ServiceRouterAndDispatcher;
use Illuminate\Http\Request;
use LINE\LINEBot;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\JoinEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

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
        if ($event instanceof JoinEvent) {
          $reply_token = $event->getReplyToken();
          $bot->replyText($reply_token, "グループに追加してくれてありがとう！");
        } else if ($event instanceof MessageEvent && $event instanceof TextMessage) {   // テキストメッセージの場合
          $text = $event->getText();              // LINEで送信されたテキスト
          $reply_token = $event->getReplyToken(); // 返信用トークン

          $service = new ServiceRouterAndDispatcher($event);
          $reply = $service->textRouterAndDispatch($text);

          if ($reply !== null) {
            $bot->replyMessage($reply_token, $reply);
          }
        }
      }
    } catch (\Exception $e) {
    }
  }
}
