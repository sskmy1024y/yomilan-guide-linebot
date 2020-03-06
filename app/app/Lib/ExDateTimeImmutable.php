<?php

/**
 * DateTimeImmutableに便利メソッドを増やしたラッパークラス。
 *
 * このクラスを直接newしないこと。
 * 代わりにUtil_DateTime::createNow等のファクトリーメソッドを使うこと。
 *
 * 便利メソッドそのものはChronosを利用して増やしている。
 * 一部のメソッドは読み書きがし辛いので、[at]deprecated付きで再定義してphpstanで警告するようにしている。
 */
final class ExDateTimeImmutable extends Carbon\Carbon
{
  // ========================== 便利ラッパーメソッドたち ==========================

  /**
   * Y-m-d 形式の文字列を返す。
   * 頻出パターンなので専用メソッドを用意してる。
   *
   * @return string
   */
  public function Ymd()
  {
    return $this->format('Y-m-d');
  }

  /**
   * Y-m-d H:i:s 形式の文字列を返す。
   * 頻出パターンなので専用メソッドを用意してる。
   *
   * @return string
   */
  public function Ymd_His()
  {
    return $this->format('Y-m-d H:i:s');
  }

  /**
   * H:i 形式の文字列を返す。
   * 頻出パターンなので専用メソッドを用意してる。
   * 
   * @return string
   */
  public function Hi()
  {
    return $this->format('H:i');
  }

  /**
   * 
   * @return array
   */
  public function DayBetween()
  {
    $start = (clone $this)->hour(0)->minute(0)->second(0);
    $end = (clone $this)->hour(24)->minute(59)->second(59);

    return [$start->Ymd(), $end->Ymd()];
  }

  // ========================== 非推奨メソッドたち ==========================

  /**
   * @deprecated 代わりにaddMinutes等のメソッドを使うこと
   * @see https://secure.php.net/manual/en/datetimeimmutable.add.php
   * @param string|DateInterval $unit (type hintingに書くと親クラスと宣言が違うので怒られる)
   * @param int $value 
   * @param bool|null $overflow
   * @return ExDateTimeImmutable
   */
  public function add($unit, $value = 1, $overflow = null)
  {
    return parent::add($unit, $value, $overflow);
  }

  /**
   * @deprecated 閏年を考慮しているか判別できないので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addYears($value)
  {
    return parent::addYears($value);
  }

  /**
   * @deprecated 閏年を考慮しているか判別できないので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addYear($value = 1)
  {
    return parent::addYear($value);
  }

  /**
   * @deprecated 「1/31の1ヶ月後は2/28なのか?」というややこしさがあるので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addMonths($value)
  {
    return parent::addMonths($value);
  }

  /**
   * @deprecated 「1/31の1ヶ月後は2/28なのか?」というややこしさがあるので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addMonth($value = 1)
  {
    return parent::addMonth($value);
  }

  /**
   * @deprecated addDaysを使うこと(表記ブレしてgrepに困る)
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addDay($value = 1)
  {
    return parent::addDay($value);
  }

  /**
   * @deprecated addMinutesを使うこと(表記ブレしてgrepに困る)
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addMinute($value = 1)
  {
    return parent::addMinute($value);
  }

  /**
   * @deprecated addSecondsを使うこと(表記ブレしてgrepに困る)
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function addSecond($value = 1)
  {
    return parent::addSecond($value);
  }

  /**
   * @deprecated 代わりにsubMinutes等のメソッドを使うこと
   * @see https://secure.php.net/manual/en/datetimeimmutable.sub.php
   * @param DateInterval $unit (type hintingに書くと親クラスと宣言が違うので怒られる)
   * @param int $value
   * @param bool|null $overflow
   * @return ExDateTimeImmutable
   */
  public function sub($unit, $value = 1, $overflow = null)
  {
    return parent::sub($unit, $value, $overflow);
  }

  /**
   * @deprecated 閏年を考慮しているか判別できないので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subYears($value)
  {
    return parent::subYears($value);
  }

  /**
   * @deprecated 閏年を考慮しているか判別できないので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subYear($value = 1)
  {
    return parent::subYear($value);
  }

  /**
   * @deprecated 「1/31の1ヶ月後は2/28なのか?」というややこしさがあるので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subMonths($value)
  {
    return parent::subMonths($value);
  }

  /**
   * @deprecated 「1/31の1ヶ月後は2/28なのか?」というややこしさがあるので使わない
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subMonth($value = 1)
  {
    return parent::subMonth($value);
  }

  /**
   * @deprecated subDaysを使うこと(表記ブレしてgrepに困る)
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subDay($value = 1)
  {
    return parent::subDay($value);
  }

  /**
   * @deprecated subMinutesを使うこと(表記ブレしてgrepに困る)
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subMinute($value = 1)
  {
    return parent::subMinute($value);
  }

  /**
   * @deprecated subSecondsを使うこと(表記ブレしてgrepに困る)
   * @param int $value
   * @return ExDateTimeImmutable
   */
  public function subSecond($value = 1)
  {
    return parent::subSecond($value);
  }

  /**
   * @deprecated 代わりにaddHoursやendOfMonth等のメソッドを使うこと
   * @see https://secure.php.net/manual/en/datetimeimmutable.modify.php
   * @param string $modify
   * @return ExDateTimeImmutable
   */
  public function modify($modify)
  {
    return parent::modify($modify);
  }

  /**
   * @deprecated 代わりにyearやmonthやdateメソッドを使うこと
   * @see https://secure.php.net/manual/en/datetimeimmutable.setdate.php
   * @param int $year
   * @param int $month
   * @param int $day
   * @return ExDateTimeImmutable
   */
  public function setDate($year, $month, $day)
  {
    return parent::setDate($year, $month, $day);
  }

  /**
   * @deprecated 代わりにhourやminuteやsecond、あるいはstartOfDay等のメソッドを使うこと
   * @see https://secure.php.net/manual/en/datetimeimmutable.settime.php
   * @param int $hour
   * @param int $minute
   * @param int $second
   * @param int $microseconds
   * @return ExDateTimeImmutable
   */
  public function setTime($hour, $minute, $second = 0, $microseconds = 0)
  {
    return parent::setTime($hour, $minute, $second, $microseconds);
  }

  /**
   * @deprecated 代わりにdiffInDays等を使うこと(時分秒全ての差が必要になることはまず無いはず)
   * @see https://secure.php.net/manual/en/datetime.diff.php
   * @param DateTimeInterface $datetime2 (type hintingに書くと親クラスと宣言が違うので怒られる)
   * @param bool $absolute
   * @return DateInterval
   */
  public function diff($datetime2 = null, $absolute = false)
  {
    return parent::diff($datetime2, $absolute);
  }
}
