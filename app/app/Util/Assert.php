<?php

use Illuminate\Support\Facades\Log;

/**
 * 検査される対象の値($subject)が条件を満たすことを保証する
 */
final class Util_Assert
{
  /**
   * is_bool()は厳しすぎて、呼び出し側でキャスト相当の処理が必要になるので、今後はこちらを使っていきたい
   *
   * @param bool|int $subject 検査される変数
   * @return bool
   */
  public static function boolLike($subject)
  {
    if (Util_Validate::isBoolLike($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be a boolean, 0 or 1 value.", $subject);
  }

  /**
   * @param int|string $subject 検査される変数
   * @return int
   */
  public static function int($subject)
  {
    // 将来的にはUtil_Assert::floatと同様にtrueを数値として通さないようにしたい。
    if (Util_Validate::isInt($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be an integral value.", $subject);
  }

  /**
   * @param int $subject 検査される変数
   * @return int
   */
  public static function nonNegativeInt($subject)
  {
    if (Util_Validate::isNonNegativeInt($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be a non-negative integral value.", $subject);
  }

  /**
   * @param int $subject 検査される変数
   * @return int
   */
  public static function positiveInt($subject)
  {
    if (Util_Validate::isPositiveInt($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be a positive integral value.", $subject);
  }

  /**
   * @param int|false $subject 検査される変数
   * @return int|false
   */
  public static function intOrFalse($subject)
  {
    if (Util_Validate::isIntOrFalse($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be an integral value or false.", $subject);
  }

  /**
   * @param  float $subject 検査される変数
   * @return float
   */
  public static function float($subject)
  {
    if (Util_Validate::isFloat($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be a float value.", $subject);
  }

  /**
   * @param int|float|string $subject 検査される変数
   * @return float
   */
  public static function positiveFloat($subject)
  {
    if (Util_Validate::isPositiveFloat($subject)) {
      return $subject;
    }
    $subject_str = self::_getSubjectString($subject);
    throw new \Exception_AssertionFailed("{$subject_str} should be a positive float value.", $subject);
  }

  /**
   * @param  string|int $subject 検査される変数
   * @return string
   */
  public static function string($subject)
  {
    if (!is_string($subject)) {
      $subject_str = self::_getSubjectString($subject);
      throw new \Exception_AssertionFailed("{$subject_str} should be a string.", $subject);
    }
    return $subject;
  }

  /**
   * @param string|false $subject 検査される変数
   * @return string|false
   */
  public static function stringOrFalse($subject)
  {
    if ($subject !== false) {
      if (!is_string($subject)) {
        $subject_str = self::_getSubjectString($subject);
        throw new \Exception_AssertionFailed("{$subject_str} should be a string or false.", $subject);
      }
    }

    return $subject;
  }

  /**
   * @param string $subject 検査される変数
   * @return string
   */
  public static function nonEmptyString($subject)
  {
    if (!is_string($subject)) {
      $subject_str = self::_getSubjectString($subject);
      throw new \Exception_AssertionFailed("{$subject_str} should be a string.", $subject);
    }
    if ($subject === '') {
      $subject_str = self::_getSubjectString($subject);
      throw new \Exception_AssertionFailed("{$subject_str} must not be an empty string.", $subject);
    }
    return $subject;
  }

  /**
   * MySQLのvarchar(n)に収まる文字列かどうか検証する。
   *
   * MySQLは設定次第(string modeでない場合)でvarchar(n)を上回る長さの文字列は切り捨ててから格納する。
   * つまり重要な値の長さチェックをMySQLに完全に任せるのは危ないので、このメソッドで明示的にチェックをする。
   *
   * pixivのDBのCHARSETはutf8mb3かutf8mb4なので、文字列の長さはUTF-8での文字数を数える。
   * ただし、家族の絵文字のような合成した結果が人間の目には1文字に見えるものは、
   * 合成する前の構成要素を個別に1文字として数える。
   *
   * @param string $subject 検査される変数
   * @param int $max_length 最大の長さ
   * @return string
   */
  public static function varchar($subject, $max_length)
  {
    Util_Assert::string($subject);
    Util_Assert::nonNegativeInt($max_length);

    // UTF-8として不正なバイト列が含まれていた場合、それを何文字と数えるか怪しいので弾く。
    // PHPとMySQLで扱いが違っていたら面倒だし、大丈夫かどうか確認するコストが高い。
    if (!mb_check_encoding($subject, 'UTF-8')) {
      throw new \Exception_AssertionFailed("{$subject}にUTF-8として不正なバイト列が含まれている");
    }

    // MySQLの各テーブルのCHARSETはutf8(=utf8mb3)かutf8mb4のいずれか。なのでmb_strlen()で文字数を数えれば良い。
    // もし$subjectにutf8mb3で格納できない文字が含まれていた場合、assertionとしては素通りするがDBへの格納は失敗する。
    // それはテーブル毎に事情が違うのでここではチェックしない。
    // 対応するCommon層のクラスで適宜Util_UTF8::containsNonBMPCharacterでチェックする。
    $length = mb_strlen($subject, 'UTF-8');
    if ($length > $max_length) {
      throw new \Exception_AssertionFailed("{$subject}の長さは{$max_length}以下を期待しているが実際は{$length}だった");
    }

    return $subject;
  }

  /**
   * @param string $subject 検査される変数
   * @param string $format 時刻のフォーマット
   * @return string
   */
  public static function customDatetimeStringByFormat($subject, $format)
  {
    Util_DateTime::assertDatetimeStringByFormat($subject, $format);
    return $subject;
  }

  /**
   * Y-m-d 形式の文字列であるかどうか検証する
   *
   * 第2引数を使って年、月、日がそれぞれ特定の文字列であるかどうかのチェックができる
   *
   * @param string $subject 検査される変数
   * @param array $rules キーには'Y'|'m'|'d'が指定できる。キーに対応する値には期待する数値文字列が入る。
   *              [
   *                'Y' => '2018',
   *                'm' => '02',
   *                'd' => '01',
   *              ]
   * @return string 検証に引っかからなかった場合、$subjectをそのまま返す
   */
  public static function ymdString($subject, array $rules = [])
  {
    $datetime = Util_DateTime::assertDatetimeStringByFormat($subject, 'Y-m-d');

    foreach ($rules as $key => $expected) {
      if (!in_array($key, ['Y', 'm', 'd'], true)) {
        throw new Exception_AssertionFailed('指定できるキーは、"Y", "m", "d"のどれかです', [
          'key' => $key,
        ]);
      }
      $actual = $datetime->format($key);
      if ($expected !== $actual) {
        throw new Exception_AssertionFailed("{$subject}の{$key}の部分に{$expected}を期待しましたが、{$actual}になっています");
      }
    }

    return $subject;
  }

  /**
   * Y-m-d H:i:s 形式の文字列であるかどうか検証する
   *
   * Util_Assert::ymdStringのような第二引数は無い。
   * このメソッドの用途的に月初のような特定の日時かどうかのチェックをする場面は無いはず。
   *
   * @param string $subject 検査される変数
   * @return string 検証に引っかからなかった場合、$subjectをそのまま返す
   */
  public static function ymdhisString($subject)
  {
    Util_DateTime::assertDatetimeStringByFormat($subject, 'Y-m-d H:i:s');
    return $subject;
  }

  /**
   * Y-m-d H:i:s 形式の文字列か、'0000-00-00 00:00:00'であるかどうか検証する
   *
   * @see Util_Assert::ymdhisString
   * @param string $subject 検査される変数
   * @return string 検証に引っかからなかった場合、$subjectをそのまま返す
   */
  public static function ymdhisOrEmptyDateTimeString($subject)
  {
    if ($subject === Util_DateTime::EMPTY_DATETIME) {
      return $subject;
    }

    return Util_Assert::ymdhisString($subject);
  }

  /**
   * @param  array $subject 検査される変数
   * @return array
   */
  public static function isArray($subject)
  {
    if (!is_array($subject)) {
      $subject_str = self::_getSubjectString($subject);
      throw new \Exception_AssertionFailed("{$subject_str} should be an array.", $subject);
    }
    return $subject;
  }

  /**
   * @param  array  $subject 検査される配列
   * @param  string $key     配列に存在すると期待されるキー
   * @return array
   */
  public static function keyExists(array $subject, $key)
  {
    if (!array_key_exists($key, $subject)) {
      $i = 0;
      $message = '';
      foreach ($subject as $v) {
        // あまり長くなると微妙なので 6 ぐらいに制限しておく
        if ($i === 6) {
          $message .= '...';
          break;
        }
        $message .= $v . ', ';
        $i++;
      }
      $message = rtrim($message, ', ');
      throw new \Exception_AssertionFailed("key={$key} doesn't exist in array({$message})");
    }
    return $subject;
  }

  /**
   * 少なくとも1つ以上のkeyが存在するか検査する
   *
   * @param array<string,mixed> $subject 検査される配列
   * @param string[] $keys               配列に存在すると期待されるキーの一覧
   * @return array<string,mixed>
   */
  public static function anyKeyExists(array $subject, array $keys)
  {
    Util_Assert::notEmpty($subject);
    Util_Assert::notEmpty($keys);

    $subject_keys = array_keys($subject);
    if (count(array_intersect($subject_keys, $keys)) == 0) {
      throw new \Exception_AssertionFailed('Arrayが含むと期待されるkeyが一つも存在しませんでした', [$subject, $keys]);
    }
    return $subject;
  }

  /**
   * @param  array    $subject 検査される配列 ex) `['id' => 123, 'name' => 'luka']`
   * @param  string[] $keys    配列に存在すると期待されるキーのリスト ex) `['id', 'name']`
   * @return array
   */
  public static function allKeysExist(array $subject, array $keys)
  {
    $missing_keys = array_diff_key(array_flip($keys), $subject);
    if (count($missing_keys) > 0) {
      $i       = 0;
      $message = '';
      foreach ($subject as $v) {
        // あまり長くなると微妙なので 6 ぐらいに制限しておく
        if ($i == 6) {
          $message .= '...';
          break;
        }
        $message .= $v . ', ';
        $i++;
      }
      $message = rtrim($message, ', ');

      $missing = implode(', ', array_keys($missing_keys));
      throw new \Exception_AssertionFailed("keys=[{$missing}] don't exist in array({$message})");
    }
    return $subject;
  }

  /**
   * @param  int|float $subject   検査される変数
   * @param  int|float $min_value 期待する最小の値
   * @return int|float
   */
  public static function min($subject, $min_value)
  {
    if ($min_value > $subject) {
      throw new \Exception_AssertionFailed("{$subject} should be greater than or equal to {$min_value}.");
    }
    return $subject;
  }

  /**
   * @param  int|float $subject   検査される変数
   * @param  int|float $max_value 期待する最大の値
   * @return int|float|string
   */
  public static function max($subject, $max_value)
  {
    if ($max_value < $subject) {
      throw new \Exception_AssertionFailed("{$subject} should be less than or equal to {$max_value}.");
    }
    return $subject;
  }

  /**
   * @param  string $subject 検査される変数
   * @param  string $preg_pattern
   * @return string
   */
  public static function regex($subject, $preg_pattern)
  {
    if (preg_match($preg_pattern, $subject) === 0) {
      throw new \Exception_AssertionFailed("{$subject} doesn't match {$preg_pattern}.");
    }
    return $subject;
  }

  /**
   * @param  mixed $subject 検査される変数
   * @param  mixed[] $collection
   * @return string
   */
  public static function stringInArray($subject, array $collection)
  {
    if (!in_array($subject, $collection, /*strict=*/ true)) {
      $array_string = '';
      for ($i = 0; $i < count($collection); $i++) {
        $array_string .= (string) $collection[$i];
        if ($i !== count($collection) - 1) {
          $array_string .= ', ';
        }
      }
      throw new \Exception_AssertionFailed("{$subject} is not in ({$array_string}).");
    }
    return $subject;
  }

  /**
   * $subject が $collection に含まれるか (数値用)
   *
   * @param  int $subject 検査される変数
   * @param  int[] $collection
   * @return int
   */
  public static function intInArray($subject, array $collection)
  {
    Util_Assert::int($subject);
    if (!in_array($subject, $collection, /*strict=*/ false)) {
      $array_string = implode(', ', $collection);
      throw new \Exception_AssertionFailed("{$subject} is not in ({$array_string}).");
    }
    return $subject;
  }

  /**
   * $subject 整数値のみの配列であるかどうか
   *
   * @param int[] $subject
   * @return int[]
   */
  public static function intSequentialArray(array $subject)
  {
    if (array_values($subject) !== $subject) {
      throw new \Exception_AssertionFailed('should be a sequential array.');
    }
    foreach ($subject as $value) {
      Util_Assert::int($value);
    }
    return $subject;
  }

  /**
   * @param  string $subject 検査される変数
   * @return string
   */
  public static function dir($subject)
  {
    if (!is_dir($subject)) {
      throw new \Exception_AssertionFailed("{$subject} is not a directory.");
    }
    return $subject;
  }

  /**
   * @param  string $subject 検査される変数
   * @return string
   */
  public static function fileExists($subject)
  {
    Util_Assert::nonEmptyString($subject);

    if (!file_exists($subject)) {
      throw new \Exception_AssertionFailed("{$subject} doesn't exist.");
    }
    return $subject;
  }

  /**
   * @param  array $subject 検査される変数
   * @return array
   */
  public static function notEmpty(array $subject)
  {
    if (count($subject) == 0) {
      throw new \Exception_AssertionFailed("the array must have some contents.");
    }
    return $subject;
  }

  /**
   * @param mixed $subject 検査される変数
   * @param mixed $comparison 一致判定を行う対象
   * @return mixed
   */
  public static function equals($subject, $comparison)
  {
    if ($subject !== $comparison) {
      throw new \Exception_AssertionFailed("{$subject} does not coincide with {$comparison}.");
    }
    return $subject;
  }

  /**
   * @param bool $subject 検査される変数
   * @return bool
   */
  public static function true($subject)
  {
    if ($subject !== true) {
      $caller = debug_backtrace(0b00, 1)[0];
      throw new \Exception_AssertionFailed(trim(file($caller['file'])[$caller['line'] - 1]));
    }
    return $subject;
  }

  /**
   * @param  array    $subject 検査される配列 ex) `['id' => 123, 'name' => 'luka']`
   * @param  string[] $keys    配列に存在すると期待されるキーのリスト ex) `['id', 'name']`
   * @return array
   */
  public static function allKeysExistForDebug(array $subject, array $keys)
  {
    $missing_keys = array_diff_key(array_flip($keys), $subject);
    if (count($missing_keys) > 0) {
      $i       = 0;
      $message = '';
      foreach ($subject as $v) {
        // あまり長くなると微妙なので 6 ぐらいに制限しておく
        if ($i == 6) {
          $message .= '...';
          break;
        }
        $message .= $v . ', ';
        $i++;
      }
      $message = rtrim($message, ', ');

      $missing = implode(', ', array_keys($missing_keys));
      Log::debug("keys=[{$missing}] don't exist in array({$message})", $subject);
    }
    return $subject;
  }

  /**
   * $subjectが既に閉じられていないresource型であるかどうかを調べる
   *
   * @param resource $subject 検査される変数
   * @return resource
   */
  public static function nonClosedResource($subject)
  {
    if (!is_resource($subject)) {
      $subject_str = self::_getSubjectString($subject);
      throw new \Exception_AssertionFailed("{$subject_str} is not resource.");
    }
    return $subject;
  }

  /**
   * subjectを文字列内に埋め込める形での表現を返す
   *
   * Exception_AssertionFailed("{$subject} should be a string.") のような形で直でメッセージに含めようとすると
   * $subject がArrayのときに文字列変換しようとしてErrorExceptionになる
   * それを抑制するためにメッセージに含める際にはこのメソッドの返り値を使う
   *
   * @param mixed $subject
   *
   * @return string
   */
  private static function _getSubjectString($subject)
  {
    if (is_array($subject)) {
      return 'Array';
    } else {
      return "{$subject}";
    }
  }
}
