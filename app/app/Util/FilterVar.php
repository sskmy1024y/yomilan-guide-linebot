<?php

use Illuminate\Support\Facades\Log;

/**
 * FilterVarの置き換え
 *
 * @package Util
 */
final class Util_FilterVar
{
  /**
   * FILTER_VALIDATE_INTのラッパー
   *
   * true を許容しないfilter_var($some, FILTER_VALIDATE_INT)
   *
   * @param  mixed $subject
   * @return int|false
   */
  public static function validateInt($subject)
  {
    if ($subject === true) { // FILTER_VALIDATE_INT は true を許容してしまうので明示的に弾く
      Log::error('FILTER_VALIDATE_INT に true が渡されています');

      return false;
    }

    return filter_var($subject, FILTER_VALIDATE_INT);
  }
}
