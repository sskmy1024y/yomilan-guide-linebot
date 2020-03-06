<?php

/**
 * Class Exception_AssertionFailed
 *
 * アサーションの失敗時に throw される Exception
 * PxvExceptionと同じ実装にしてある理由は、例外が発生した際に、その値や配列の中身をエラーログで確認できるようにするため。
 *
 */
class Exception_AssertionFailed extends \RuntimeException
{
  private $_debug_info;
  // debug_info以外に追加データを持たせたい具体例があれば知りたい

  /**
   * @param string $message    エラーメッセージ
   * @param mixed  $debug_info デバッグ用にエラーログに出力したいデータ
   */
  public function __construct($message, $debug_info = null)
  {
    parent::__construct($message);
    $this->_debug_info = $debug_info;
  }

  // Exception_HttpStatusHandlerでmethod_existsしたいため、staticにしない
  public function getDebugInfo()
  {
    return $this->_debug_info;
  }
}
