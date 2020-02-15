<?php

/**
 * バリデーションのロジックを書くクラス
 *
 * 値を受け取り、バリデーション処理を行い、成否をtrue/falseで返すメソッドだけを置く
 * ParamHelperやUtil_Assertなどに新しくメソッドを追加する場合は、判定ロジックはこのクラスに書き、ラップするようにする
 *
 * @package Util
 */
final class Util_Validate
{
  /**
   * 与えた値が真偽値とみなせるかどうかを判定する
   *
   * is_bool()は厳しすぎて、呼び出し側でキャスト相当の処理が必要になるので、今後はこちらを使っていきたい
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isBoolLike($subject)
  {
    if (is_bool($subject)) {
      return true;
    }
    // echo '-0' ? 'true' : 'false'; => trueなので、'-0'と'+0'はboollikeではない
    if ($subject === '-0' || $subject === '+0') {
      return false;
    }
    $filtered = Util_FilterVar::validateInt($subject);
    return is_int($filtered) && ($filtered == 0 || $filtered == 1);
  }

  /**
   * 与えた値が整数であるかどうかを判定する
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isInt($subject)
  {
    return Util_FilterVar::validateInt($subject) !== false;
  }

  /**
   * 与えた値が非負整数であるかどうかを判定する(0を含む)
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isNonNegativeInt($subject)
  {
    return Util_FilterVar::validateInt($subject) !== false && $subject >= 0;
  }

  /**
   * 与えた値が非負整数の配列であるかどうかを判定する(0を含む)
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isNonNegativeIntList($subject)
  {
    if (!is_array($subject)) {
      return false;
    }

    if (array_values($subject) !== $subject) {
      return false;
    }

    foreach ($subject as $value) {
      if (!Util_Validate::isNonNegativeInt($value)) {
        return false;
      }
    }

    return true;
  }

  /**
   * 与えた値が正の整数であるかどうかを判定する(0は含まない)
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isPositiveInt($subject)
  {
    return Util_FilterVar::validateInt($subject) !== false && $subject > 0;
  }

  /**
   * 与えた値が正の整数の配列であるかどうかを判定する(0は含まない)
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isPositiveIntList($subject)
  {
    if (!is_array($subject)) {
      return false;
    }

    if (array_values($subject) !== $subject) {
      return false;
    }

    foreach ($subject as $value) {
      if (!Util_Validate::isPositiveInt($value)) {
        return false;
      }
    }

    return true;
  }

  /**
   * 与えられた値が浮動小数点数であるかどうかを判定する
   *
   * 受け入れパターンは以下の通り
   * * 全てのint型･float型の値を受け入れる
   * * string型の実数 (数値･符号･小数点･基数eまたはE、からなる、実数としてみなせる値) は受け入れる
   * * 例外的に、仮数部が '01' のように0パディングされているstring値は受け入れない (想定されるユースケースがなく、isIntも受け入れない値であるため)
   * * 指数部は '1.0e01' のように0パディングされていても受け入れる (PHP自体がこの表記を受け入れるうえ、外部、特にBigQuery由来のデータにこの表記のものが含まれるため)
   * * string型の 'INF' と 'NAN' およびそれらの表記ゆれは受け入れない (想定されるユースケースがなく、サポートする無限大･非数の表現を中立に選択することもできないため)
   *
   * @param int|float|string $subject
   * @return bool
   */
  public static function isFloat($subject)
  {
    // 整数部が含まれる場合は、小数部が含まれる場合も含まれない場合も受け入れる
    // 整数部が含まれない場合は、小数部が含まれる場合のみ受け入れる
    // 指数部が含まれる場合は、eまたはEの後ろに整数が含まれる場合のみ受け入れる
    $pattern = '/\A[+-]?(?:(?:[1-9][0-9]*|0)(?:\.[0-9]+)?|(?:\.[0-9]+))(?:[eE][+-]?(?:[0-9]+))?\z/';
    return is_int($subject) || is_float($subject) || (is_string($subject) && preg_match($pattern, $subject));
  }

  /**
   * 与えた値が正の浮動小数点数であるかどうかを判定する(0は含まない)
   *
   * @param int|float|string $subject 検査される変数
   * @return bool
   */
  public static function isPositiveFloat($subject)
  {
    return Util_Validate::isFloat($subject) && $subject > 0;
  }

  /**
   * 与えた値が整数またはfalseであるかどうかを判定する
   *
   * @param mixed $subject 検査される変数
   * @return bool
   */
  public static function isIntOrFalse($subject)
  {
    return $subject === false || Util_FilterVar::validateInt($subject) !== false;
  }

  /**
   * @param  mixed $val
   * @return bool
   */
  public static function isIntOrIntegerString($val)
  {
    if (is_int($val)) {
      return true;
    }
    return (string) $val === (string) (int) $val;
  }

  /**
   * 与えられた文字列が日付形式かどうか、日付として正しいかチェックする
   * 例) 20160805 -> true
   * 例) 20160230 -> false
   * 例) 20161420 -> false
   * 例) 2016-01-01 -> false
   * 例) 2016/01/01 -> false
   *
   * @link http://takafumi-s.hatenablog.com/entry/2015/06/06/192443
   * @param string $date 日付形式の文字列
   * @return bool
   */
  public static function isDateFormat($date)
  {
    if (!is_string($date)) {
      return false;
    }

    $reg_date = '/^(?P<year>[0-9]{4})(?P<month>[0-9]{2})(?P<day>[0-9]{2})$/';
    return preg_match($reg_date, $date, $m) === 1 && checkdate($m['month'], $m['day'], $m['year']);
  }

  /**
   * @param  mixed $subject
   * @return bool
   */
  public static function isStringList($subject)
  {
    if (!is_array($subject)) {
      return false;
    }

    if (array_values($subject) !== $subject) {
      return false;
    }

    foreach ($subject as $value) {
      if (!is_string($value)) {
        return false;
      }
    }

    return true;
  }
}
