<?php

/**
 * 日時処理をまとめたクラス
 *
 * 日時処理は罠が多く、色々なやり方が有って書き方がバラバラになりがちなので、必ずこのクラスを使うこと。
 * つまり、このクラスを経由せず直接time関数やDateTimeクラスを利用して日時処理を書いてはならない。
 *
 * このクラスにまとめられている処理は大まかに以下の通り:
 *
 * - 最低限必要な標準関数のラッパー (Util_DateTime::time() 等)
 * - ExDateTimeImmutable インスタンスを作るラッパー (Util_DateTime::createFromFormat() 等)
 * - 以上のメソッドを利用する頻出パターンをメソッド化したもの (Util_DateTime::Ymd_His() 等)
 *
 * @package Util
 */
final class Util_DateTime
{
  /** 空の日時の文字列表現 */
  const EMPTY_DATETIME = '0000-00-00 00:00:00';

  /** 空の日の文字列表現 */
  const EMPTY_DATE = '0000-00-00';

  const DEFAULT_FORMAT = 'Y-m-d H:i:s';

  /**
   * time() と同じ。
   *
   * 日時処理はUtil_DateTimeを使う形にする為に用意されている。
   *
   * @link   http://php.net/time
   * @return int
   */
  public static function time()
  {
    return \time();
  }

  /**
   * date($format) と同じ。
   *
   * 日時処理はUtil_DateTimeを使う形にする為に用意されている。
   * どうしても特殊なフォーマットで文字列化したい場合にこのメソッドを使う。
   * 頻繁に使うフォーマットに関しては、Util_DateTime::Ymd_His等の専用メソッドが
   * 用意されているのでそちらを使う。
   *
   * @link   http://php.net/date
   * @param  string $format
   * @param  int    $timestamp
   * @return string
   */
  public static function date($format, $timestamp = null)
  {
    if ($timestamp !== null) {
      return \date($format, $timestamp);
    }
    return \date($format);
  }

  /**
   * Util_DateTime::date('Y-m-d H:i:s') と同じ
   *
   * 頻出パターンなので専用メソッドにしている。
   *
   * @param  int    $timestamp
   * @return string
   */
  public static function Ymd_His($timestamp = null)
  {
    return Util_DateTime::date(Util_DateTime::DEFAULT_FORMAT, $timestamp);
  }

  /**
   * Util_DateTime::date('Y-m-d') と同じ
   *
   * 頻出パターンなので専用メソッドにしている。
   *
   * @param  int    $timestamp
   * @return string
   */
  public static function Ymd($timestamp = null)
  {
    return Util_DateTime::date('Y-m-d', $timestamp);
  }

  /**
   * strtotime($format) と同じ
   *
   * 日時処理は Util_DateTime を使う形にする為に用意されている。
   *
   * @deprecated 新規に書くなら Util_DateTime::createFromYmdHis() 等のインスタンスのメソッドを使った方が読み書きしやすい (このメソッドは互換性の為に残してある)
   *
   * @link http://php.net/strtotime
   * @param string   $format http://php.net/date
   * @param int|null $timestamp
   * @return int|false
   */
  public static function strtotime($format, $timestamp = null)
  {
    if ($timestamp !== null) {
      return \strtotime($format, $timestamp);
    }
    return \strtotime($format);
  }

  /**
   * @deprecated
   * @param string            $time
   * @param DateTimeZone|null $timezone
   * @return \DateTime
   */
  public static function date_create($time = 'now', \DateTimeZone $timezone = null)
  {
    if ($timezone === null) {
      return new \DateTime(Util_DateTime::date('c', Util_DateTime::strtotime($time)));
    } else {
      return new \DateTime(Util_DateTime::date('c', Util_DateTime::strtotime($time)), $timezone);
    }
  }

  /**
   * 有効な日付かどうかを判定(checkdateを親しみやすくしたラッパー関数)
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @return bool
   */
  public static function isValidYmd($year, $month, $day)
  {
    return checkdate($month, $day, $year);
  }

  /**
   * Util_DateTime::createFromFormat() をアサーションの文脈で利用するためのラッパーメソッド
   *
   * もともと、Util_Assert::customDatetimeStringByFormatから使うためのメソッドだったが、
   * Util_Assert::ymdString() からも使われるようになったという経緯がある。
   *
   * このアサーションメソッドの返り値は入力された検証値そのものではなく、
   * 検証の過程で生成されたExDateTimeImmutableオブジェクトを返す。
   *
   * @param string $string 時刻文字列
   * @param string $format フォーマット
   * @return ExDateTimeImmutable
   */
  public static function assertDatetimeStringByFormat($string, $format = 'Y-m-d H:i:s')
  {
    try {
      // Util_DateTime::createFromFormatを呼び出すと検証されるので、重複を減らすためとりあえず使っておく
      return Util_DateTime::createFromFormat($format, $string);
    } catch (\Throwable $e) {
      // 例外の型をException_AssertionFailedにするためにラップする
      // この関数はアサーションメソッドからのみ呼ばれる想定なので、
      // Util_Assertの他のメソッドと例外を合わせる（例外の型を検査する必要があるケースもあるので揃えたい）
      throw new Exception_AssertionFailed($e->getMessage());
    }
  }

  /**
   * 時刻文字列が指定したフォーマットを満たしているかどうかを返す
   *
   * assertではなく、判定したいときに使用する。
   * assertする際には Util_Assert::customDatetimeStringByFormat() を使用すること。
   *
   * @param string $datetime_string 時刻文字列
   * @param string $format フォーマット
   * @return bool
   */
  public static function isValidDatetimeString($datetime_string, $format = 'Y-m-d H:i:s')
  {
    try {
      // Util_DateTime::createFromFormatを呼び出すと検証される。
      Util_DateTime::createFromFormat($format, $datetime_string);
    } catch (\Throwable $e) {
      return false;
    }
    return true;
  }

  /**
   * 現在の日時と任意の日時とのdiffから DateInterval を取得する
   *
   * @param string $datetime_string 時刻文字列
   * @param string $format フォーマット
   * @return DateInterval
   *
   * @see https://secure.php.net/manual/ja/datetime.diff.php
   * @see https://secure.php.net/manual/ja/class.dateinterval.php
   */
  public static function getDateIntervalFromNowByDiff($datetime_string, $format = 'Y-m-d H:i:s')
  {
    $now_datetime = Util_DateTime::createNow();
    $base_datetime = Util_DateTime::createFromFormat($format, $datetime_string);
    $date_interval = $now_datetime->diff($base_datetime);

    // インスタンス取得時にエラーをチェックしているため実際にここでfalseになることは無さそう
    if ($date_interval === false) {
      throw new Exception_AssertionFailed('$date_interval is false');
    }

    return $date_interval;
  }

  /**
   * 現在時刻の DateTime のインスタンスを作って返す
   *
   * @return DateTime
   */
  public static function createNow()
  {
    return Util_DateTime::createFromFormat('Y-m-d H:i:s', Util_DateTime::Ymd_His());
  }

  /**
   * Y-m-d H:i:s形式の文字列を DateTime のインスタンスに変換する
   *
   * @param string $string Y-m-d H:i:s形式
   * @return DateTime
   */
  public static function createFromYmdHis($string)
  {
    Util_Assert::string($string);

    return Util_DateTime::createFromFormat('Y-m-d H:i:s', $string);
  }

  /**
   * Y-m-d形式の文字列を DateTime のインスタンスに変換する。
   *
   * @param string $string Y-m-d形式
   * @return DateTime
   */
  public static function createFromYmd($string)
  {
    Util_Assert::string($string);

    // startOfDayしておかないと時分秒が現在時刻になる。
    // 日付同士の演算を行う場合に罠になるので00:00:00にしておく。
    return Util_DateTime::createFromFormat('Y-m-d', $string)->setTime(0, 0, 0);
  }

  /**
   * DateTime のインスタンスを作って返す
   *
   * 基本的には Util_DateTime::createFromYmdHis() か Util_DateTime::createFromYmd() を使うこと。
   * それ以外の特殊な形式の日時文字列を変換する場合なら使っても良い。
   * 
   * # 罠に注意
   * 指定したフィールド以外は現在日時で設定される。時間差の計算で罠になりやすい。
   * 例: Util_DateTime::createFromFormat('Y-m-d', $string) のように使うと、時刻部分は現在時刻になる。
   *
   * @param string $format フォーマット('U'を使うと戻り値はUTCになるので注意)
   * @param string $string 時刻文字列
   * @return DateTime JSTとして解釈した日時($formatによって変わるので注意)
   * @see http://php.net/manual/en/datetime.createfromformat.php
   */
  public static function createFromFormat($format, $string)
  {
    Util_Assert::string($string);
    Util_Assert::string($format);

    try {
      // timezoneを渡さないとまずいことがあると聞いたが、確認できていない。念のため渡しておく。
      $datetime = DateTime::createFromFormat($format, $string, new DateTimeZone('Asia/Tokyo'));
    } catch (\Throwable $e) {
      // DateTime::createFromFormat() は失敗すると InvalidArgumentException を投げる。
      // メッセージがバラバラでものによっては日時情報のことか分からないことがあるし、
      // テストを書く時にも困る場合があるので、共通のプレフィックスを付けて再throwする。
      throw new InvalidArgumentException('日時情報の作成に失敗: ' . $e->getMessage());
    }

    if (!($datetime instanceof DateTime)) {
      // DateTime::createFromFormat() は失敗すると InvalidArgumentException を投げる。
      // なのでこのif文に引っかかることは有り得ないはずだが、一応チェックしておく。
      throw new Exception_AssertionFailed('日時情報の作成に失敗: ' . var_export($datetime, true));
    }
    self::_checkLastErrors();
    return $datetime;
  }

  public static function createUTC($input = 'now', DateTimeZone $timezone = null)
  {
    Util_Assert::string($input);
    $datetime = new DateTime($input, $timezone);
    self::_checkLastErrors();
    return $datetime->setTimezone(new DateTimeZone('UTC'));
  }

  /**
   * 日時文字列を受け取り、それに対応する日本語の曜日を返す
   *
   * @param string $date 日時文字列
   * @return string 曜日文字列（'日'〜'土'）
   * @phan-return '日'|'月'|'火'|'水'|'木'|'金'|'土'
   */
  public static function dateToWeekDay($date)
  {
    return ['日', '月', '火', '水', '木', '金', '土'][Util_DateTime::date('w', Util_DateTime::strtotime($date))];
  }

  /**
   * DateTime クラスのメソッド呼び出しでエラーが発生したかチェックする
   *
   * 日付/時刻文字列のパースを行うメソッドの直後で必ずこのメソッドを呼び出すこと。
   * 該当するメソッドは以下の通り:
   *
   * - new DateTime()
   * - DateTime::createFromFormat()
   *
   * @see http://jp2.php.net/manual/ja/datetime.getlasterrors.php
   */
  private static function _checkLastErrors(): void
  {
    $errors = DateTime::getLastErrors();
    if ($errors['warning_count'] != 0 || $errors['error_count'] != 0) {
      throw new Exception_AssertionFailed('日時情報の作成に失敗: ' . var_export($errors, true));
    }
  }
}
