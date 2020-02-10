<?php

use Illuminate\Support\Facades\Log;

/**
 * PHPの言語機能によるキャストは柔軟すぎて脆弱なコードになりやすいので、必要な機能に絞った型変換を提供する
 *
 * - 不必要なキャストは基本的に行わない
 * - もしこのクラスに存在しないケースでキャストしたい場合は独断で追加せず、相談すること
 * - このメソッドの呼び出しはtry-catchしないこと (例外=バグ)
 *
 * 変換されるパターン、例外を吐く場合などは PixivTest\Util\Cast のテストコードを参考のこと
 */
final class Util_Cast
{
  /**
   * 整数っぽい値をstringに変換する
   *
   * @param  string|int|float $v
   * @return string
   */
  public static function toString($v)
  {
    $filtered = Util_FilterVar::validateInt($v);
    if (is_int($filtered)) {
      return (string) $filtered;
    }
    throw new Exception_AssertionFailed("should be a int value.");
  }

  /**
   * 整数っぽい値をintに変換する
   *
   * @param  string|int|float $v
   * @return int
   */
  public static function toInt($v)
  {
    $filtered = Util_FilterVar::validateInt($v);
    if (is_int($filtered)) {
      return $filtered;
    }
    throw new Exception_AssertionFailed("should be a int value.");
  }

  /**
   * 浮動小数点値を浮動小数点にする
   *
   * @param string|int|float $v
   * @return float
   */
  public static function toFloat($v)
  {
    if (Util_Validate::isFloat($v)) {
      return floatval($v);
    }
    throw new \Exception_AssertionFailed('value is not a float value.');
  }

  /**
   * 0|1っぽい値または真偽値を、0|1に変換する
   * DBには0|1で格納されているが、コード上ではtrue|falseを使っている箇所があるため、将来的に0|1に統一していくためのメソッド
   *
   * @param  string|int|float|bool $v
   * @return int
   */
  public static function toBooleanInt($v)
  {
    // '+0' と '-0' は曖昧比較では0と等しくなるが、真偽値として解釈すると非空文字列としてtrueになってしまう。
    // なのでUtil_Validate::isBoolLike()だけでは処理できず、特別に分岐が必要
    if ($v === '+0' || $v === '-0') {
      return 0;
    }
    if (Util_Validate::isBoolLike($v)) {
      return $v ? 1 : 0;
    }
    throw new Exception_AssertionFailed("should be a boolean, 0 or 1 value.");
  }

  /**
   * 連想配列をオブジェクトに変換する
   *
   * @param array<string,mixed> $v 変換したい連想配列
   */
  public static function toObject(array $v): stdClass
  {
    return (object) $v;
  }

  /**
   * 配列のキーをstringにキャストする
   *
   * 配列のキーが「整数っぽい値」のときにキーが確実に文字列になるようにするために利用。
   * foreach の最初の文でキーが文字列であることを保証する目的でのみ利用する。
   *
   * @param  string|int $v
   * @return string
   *
   */
  public static function toStringForArrayKey($v)
  {
    if (is_string($v) || is_int($v)) {
      return (string) $v;
    }

    throw new \Exception_AssertionFailed('value is invalid array key (it should be string or int value.)');
  }

  /**
   * 作品タグを表す値をstringにキャストする。
   *
   * 作品タグは基本的に文字列だが、arrayのキーにタグを使う処理を経由すると、
   * 「2018」のようなタグの場合は文字列から整数に変換されるため、
   * 文字列だと思って比較等をして想定外の結果を生じる事がある。
   *
   * @param  string|int $v
   * @return string
   *
   */
  public static function toStringForWorkTag($v)
  {
    if (is_string($v) || is_int($v)) {
      return (string) $v;
    }

    throw new \Exception_AssertionFailed('value is invalid work tag (it should be string or int value.)');
  }

  /**
   * 整数っぽい値をintに変換する (キャスト廃止サポート用)
   *
   * @param  string|int|float $v
   * @return int
   */
  public static function toIntForDebug($v)
  {
    $filtered = Util_FilterVar::validateInt($v);
    if (is_int($filtered)) {
      return $filtered;
    }
    Log::debug('Cast::toIntForDebug: not a int value', $v);
    return (int) $v;
  }
}
