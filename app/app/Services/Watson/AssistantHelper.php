<?php

namespace App\Services\Watson;

use Util_Assert;
use GuzzleHttp\Client;

/**
 * Call Watson Assistant API
 */
class Assistant_Helper
{
  /**
   * this method returns WatsonAssitant's Response.
   * 
   * @param string $spokenWord message from user
   * @param array $context　context from before message
   * @return array Response From Watson Assistant
   */
  public static function postMessage(string $spokenWord, array $context = [])
  {
    $wa = new Watson_Assistant($spokenWord, $context);
    return $wa->getAll();
  }
}
