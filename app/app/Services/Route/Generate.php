<?php

namespace App\Services\Route;

use App\Models\Area;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Location;
use App\Services\LINEBot\RouteHelper;
use Util_Assert;
use Util_DateTime;
use ExDateTimeImmutable;
use Illuminate\Support\Facades\Log;

class Route_Generate
{
  /** @var ExDateTimeImmutable 入園開始時間 */
  private $start_time;

  /** @var ExDateTimeImmutable 閉園時間 */
  private $end_time;

  /** @var Location 現在地 */
  private $location;

  /** @var Facility[] 施設の候補リスト */
  private $facilities = [];

  /**
   * コンストラクタの作成
   * 
   * @param ExDateTimeImmutable $start
   * @param ExDateTimeImmutable $end
   * @param Location $location
   */
  public function __construct($start, $end = null, $location = null)
  {
    if ($end === null) {
      $end = (clone $start)->setTime(18, 0); // FIXME: setTimeは、APIから終園時間を取得したものを使用する
    }
    if ($location === null) {
      $location = Location::enterance();
    }

    $this->location = $location;
    $this->start_time = $start;
    $this->end_time = $end;
  }

  /**
   * 現在地を設定
   * 
   * @param Location $location
   */
  public function setCurrentLocation($location)
  {
    $this->location = $location;
  }

  /**
   * 開始時間を設定
   * @param ExDateTimeImmutable $start_time
   */
  public function setStartTime($start_time)
  {
    $this->start_time = $start_time;
  }

  /**
   * 終了時間を設定
   * @param ExDateTimeImmutable $start_time
   */
  public function setEndTime($end_time)
  {
    $this->end_time = $end_time;
  }

  /**
   * Facilityを候補リストに追加する
   * @param Facility $facility
   */
  public function setFacility($facility)
  {
    $this->facilities[] = $facility;
  }

  /**
   * 生成
   * 
   * @return array
   */
  public function make(): array
  {
    $usable_time = $this->start_time->diffInMinutes($this->end_time);

    if ($this->start_time->hour < 13) {
      $lanch = Facility::where("type", "=", FacilityType::RESTAURANT)->inRandomOrder()->first();
      $lanch_time = $lanch->require_time + 20;  // 移動 + 待ち時間を20にしてみる
    } else {
      $lanch_time = 0;
    }

    // 遊べる時間を自動計算
    $active_time = $usable_time - $lanch_time;
    Log::info("開始時間: " . $this->start_time->Hi());

    $reaming = 0;
    do {
      $ids = array_column($this->facilities, "id");
      // エリアごとの施設を取得
      $pickup_list = self::_getAttractionsEveryArea($ids);
      // 施設リストと周回時間を取得
      list($_facilities, $orbit_time) = self::newFacilitiesAndOrbitTimeHelper($pickup_list);

      Log::info("アトラクション数: " . count($_facilities));
      Log::info("ここまでの周回完了時間: " . (clone $this->start_time)->addMinutes($orbit_time)->Hi());

      if ($orbit_time > $active_time && count($pickup_list) > 1) {
        // 時間超過していても、pickupが複数あれば1つずつ挿入してみる
        foreach ($pickup_list as $pickup) {
          list($_facilities, $orbit_time) = self::newFacilitiesAndOrbitTimeHelper(array($pickup));
          if ($orbit_time > $active_time) {
            break;
          } else {
            $this->facilities = $_facilities;
          }
          // どちらにせよ時間超過している = 検索終了
          break;
        }
      } else if ($orbit_time == $reaming || $orbit_time > $active_time) {
        // 直前の時間と同じならこれ以上施設がない = 検索終了
        break;
      }

      // 値を更新
      $this->facilities = $_facilities;
      $reaming = $orbit_time;
    } while ($reaming < $active_time);

    if ($lanch_time > 0) {
      $this->facilities = self::_mergeLanchFacility($lanch);
    }

    Log::info("終了時間: " . (clone $this->start_time)->addMinutes($reaming + $lanch_time)->Hi());
    Log::info("アトラクション数: " . count($this->facilities));

    return [
      'use_time' => $reaming,
      'facilities' => $this->facilities,
    ];
  }

  /**
   * 追加したいFacility配列を渡して、新しい候補一覧を生成・周回時間を返すHelper関数
   * 
   * 使用箇所が複数あったので関数に切り分けた
   * 
   * @param Facility[] $_facilities 追加候補のFacility
   * @return array `['list' => Facility[], 'orbit' => int]` 新しい候補リストと周回時間
   */
  private function newFacilitiesAndOrbitTimeHelper(array $_facilities): array
  {
    //候補一覧とマージして施設同士の近さで並び替える
    $facilities = self::_sortByDistanceFromFacilities(
      array_merge($_facilities, $this->facilities)
    );
    // 施設を回るのにかかる時間を計算
    $orbit_time = RouteHelper::orbitTime($facilities, $this->location);

    return array(
      $facilities,
      $orbit_time,
    );
  }

  /**
   * エリア毎にアトラクションをピックアップする
   * 
   * @param int[] $exclude_ids 除外する施設のID
   * @return Facility[]
   */
  private function _getAttractionsEveryArea(array $exclude_ids = []): array
  {
    $areas = Area::all();

    $facilities = [];
    foreach ($areas as $area) {
      $area_facilities = $area->facilies()
        ->where('type', '=', FacilityType::ATTRACTION)
        ->where('enable', '=', true)
        ->whereNotIn('id', $exclude_ids);  // 同じアトラクションを排除

      // TODO: 取得条件をユーザによって変える（子連れ、年代等）
      // $area_facilities = $area_facilities->where("for_child", "=", 0);

      $facility = $area_facilities->inRandomOrder()->first();

      if ($facility !== null) {
        $facilities[] = $facility;
      }
    }
    return $facilities;
  }

  /**
   * 施設の近さに基づいてリストを並び替えて返す
   * 
   * @param Facility[] $_facilities 
   * @return Facility[]
   */
  private function _sortByDistanceFromFacilities(array $_facilities)
  {
    Util_Assert::notEmpty($_facilities);

    $next = self::_sortByDistanceFromCurrentLocation($_facilities);
    $facilities = [];
    do {
      $facilities = array_merge($facilities, $next);
      if (($key = array_search($next, $_facilities)) !== false) {
        unset($_facilities[$key]);
        $next = $next->getMostNearFacility($_facilities);
      } else {
        break;
      }
    } while (count($_facilities) > 0);

    return $facilities;
  }

  /**
   * 施設の配列情報を、現在地からの近さで並べ替えて返す
   * 
   * @param Facility[] $facilities 施設の配列
   * @return Facility[]
   */
  private function _sortByDistanceFromCurrentLocation(array $facilities): array
  {
    Util_Assert::notEmpty($facilities);
    foreach ($facilities as $key => $facility) {
      $sort[$key] = $facility->distance($this->location);
    }
    array_multisort($sort, SORT_ASC, $facilities);
    return $facilities;
  }

  /**
   * お昼ご飯の場所を、時間的に適切な順に挿入して返す
   * 
   * @param Facility $lanch お昼ご飯の場所
   * @return array 
   */
  private function _mergeLanchFacility($lanch)
  {
    ksort($this->facilities);
    if ($this->start_time->hour <= 11 && $this->start_time->minute <= 30) {
      $lanch_start = (clone $this->start_time)->setTime(11, 30);
    } else {
      $lanch_start = (clone $this->start_time);
    }
    do {
      $lanch_end = (clone $this->start_time)->setTime(rand(12, 13), rand(0, 59));
      if ($lanch_end->diffInMinutes($lanch_start, false) < 0) {
        continue;
      }
    } while ($lanch_end->hour != 12 && !($lanch_end->hour == 13 && $lanch_end->minute <= 30));

    $_facilities = [];
    /**
     * 1. 飲食店の近くのアトラクションを探す
     * 2. アトラクションまでの時間が11時半以降13時半以前ならば、挿入
     * 3. 11時半以前なら、次のアトラクションで2.を行う
     * 3. 13時半以降なら、前のアトラクションで2.を行う
     */
    $next = $lanch->getMostNearFacility($this->facilities);
    if ($next === null) {
      throw new \Exception_AssertionFailed("レストランの近くにアトラクションが見つからない");
    }

    while (count($_facilities) <= count($this->facilities)) {
      $start_time = (clone $this->start_time);
      $key = array_search($next->id, array_column($this->facilities, 'id'));
      if ($key === false) {
        throw new \Exception_AssertionFailed('該当する施設が見つかりませんでした', $next);
      } else if ($key <= 0) {
        $_facilities = array_merge(array($lanch), $this->facilities);
        break;
      }

      $first_helf = array_slice($this->facilities, 0, $key);
      $letter_helf = array_slice($this->facilities, $key);
      $comp_time = $start_time->addMinutes(RouteHelper::orbitTime($first_helf, $this->location));

      if ($comp_time->gte($lanch_start) && $comp_time->lte($lanch_end)) {
        $_facilities = array_merge($first_helf, array($lanch), $letter_helf);
        break;
      } else if ($comp_time->lte($lanch_end)) {   // 11:30以前
        $next = $this->facilities[$key + 1];
      } else if ($comp_time->gte($lanch_start)) { // 13:30以降
        $next = $this->facilities[$key - 1];
      } else {
        throw new \Exception_AssertionFailed('お昼時間に挿入できませんでした');
      }
    };

    return $_facilities;
  }
}
