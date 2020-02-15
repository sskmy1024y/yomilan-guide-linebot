<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LINE\LINEBot;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LinebotController extends Controller
{
  public function callback(Request $request)
  {
    $channel_secret = env('LINE_CHANNEL_SECRET');
    $access_token = env('LINE_ACCESS_TOKEN');
    $request_body = $request->getContent();
    $signature = $request->getHeader(HTTPHeader::LINE_SIGNATURE);

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
        if ($event instanceof MessageEvent && $event instanceof TextMessage) {   // テキストメッセージの場合
          $text = $event->getText();              // LINEで送信されたテキスト
          $reply_token = $event->getReplyToken(); // 返信用トークン

          $replying_message = new TextMessageBuilder($text);
          $bot->replyMessage($reply_token, $replying_message);
        }
      }
    } catch (\Exception $e) {
    }
  }
}
