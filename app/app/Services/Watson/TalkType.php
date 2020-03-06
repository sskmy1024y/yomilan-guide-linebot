<?php

class TalkType
{
  const EVENT = 'イベント関連';
  const COURSE_REGENERATE = 'コース再生成';
  const COURSE_REVIEW = 'コース再表示';
  const PLANING = 'プランニング関連';
  const RESTAURANT = '飲食店関連';
  const HELP = 'ヘルプ関連';

  /**
   * 会話内容の型(TalkType)をintentを元に返す
   * @param string $intent intent
   * @return TalkType|null
   */
  public static function getType(string $intent)
  {
    switch ($intent) {
      case 'イベント関連':
        return TalkType::EVENT;
      case 'コース再生成':
        return TalkType::COURSE_REGENERATE;
      case 'コース再表示':
        return TalkType::COURSE_REVIEW;
      case 'プランニング関連':
        return TalkType::PLANING;
      case '飲食店関連':
        return TalkType::RESTAURANT;
      case 'ヘルプ関連':
        return TalkType::HELP;
      default:
        return null;
    }
  }
}
