<?php

namespace App\Services\Watson;

use Util_Assert;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use TalkType;

/**
 * Call Watson Assistant API
 */
class Watson_Assistant
{
  /** @var string リクエストする文章 */
  private $spokenWord;
  /** @var array context  */
  private $context;
  /** @var array watsonからの返答内容 */
  private $response;

  public function __construct(string $spokenWord, array $context = [])
  {
    $this->spokenWord = $spokenWord;
    $this->context = $context;

    if (count($this->context) > 0) {
      $requestData  = json_encode(['input' => ['text' => $this->spokenWord], 'context' => $this->context]);
    } else {
      $requestData  = json_encode(['input' => ['text' => $this->spokenWord]]);
    }
    $headers = ['Content-Type' => 'application/json', 'Content-Length' => strlen($requestData)];
    $curlOpts = [
      CURLOPT_USERPWD        => config('watson.assistant_user_name') . ':' . config('watson.assistant_password'),
      CURLOPT_POSTFIELDS     => $requestData
    ];
    $path         = config('watson.assistant_workspace_id') . '/message?version=' . config('watson.assistant_version');
    $guzzleClient = new Client(['base_uri' => config('watson.assistant_api_url') . '/v1/workspaces/']);
    $response = $guzzleClient->request('POST', $path, ['headers' => $headers, 'curl' => $curlOpts])->getBody()->getContents();
    $this->response = json_decode($response, true);
  }

  /**
   * watsonの応答テキストがあれば返す
   * @return string|false 応答がテキストでない場合false
   */
  public function replyText()
  {
    Util_Assert::notEmpty($this->response);
    return current($this->response['output']['text']) ?? false;
  }

  /**
   * intentsを返す
   * @return array|false intetnsがない場合false
   */
  public function intents()
  {
    Util_Assert::notEmpty($this->response);
    return $this->response['intents'] ?? false;
  }

  /**
   * 最初にヒットしたintentsをresponseの
   * @return TalkType|null
   */
  public function topIntents()
  {
    $intent = current($this->intents())['intent'] ?? false;
    if ($intent === false) {
      return null;
    }
    return TalkType::getType($intent);
  }

  /**
   * Watsonのresponseを生データで取得する
   * @return array
   */
  public function getAll()
  {
    return $this->response;
  }
}
