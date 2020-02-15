<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LinebotController extends Controller
{
  public function callback(Request $request)
  {
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
    $args = [
      'channelSecret' => $channel_secret,
    ];

    if (env('LINE_SIMULATOR')) {
      $args['endpointBase'] = "http://host.docker.internal:8123";
    }

    $bot = new LINEBot($client, $args);

    try {
      $events = $bot->parseEventRequest($request_body, $signature);

      foreach ($events as $event) {
        if ($event instanceof MessageEvent && $event instanceof TextMessage) {   // テキストメッセージの場合
          $text = $event->getText();              // LINEで送信されたテキスト
          $reply_token = $event->getReplyToken(); // 返信用トークン

          $bot->replyText($reply_token, $text);
        }
      }
    } catch (\Exception $e) {
    }
  }
}
