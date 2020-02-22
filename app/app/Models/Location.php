<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class Location
{
  /** @var int 地球の半径 */
  private const KMRADIUS = 6371;

  /** @var float 緯度 */
  public $latitude;
  /** @var float 経度 */
  public $longitude;

  /**
   * @param float $latitude Latitude
   * @param float $longitude Longitude
   */
  public function __construct($latitude = 35.6242, $longitude = 139.5174)
  {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
  }

  /**
   * 指定した在票との距離を返す
   * 
   * @param Location $location
   * @return float 距離(km)
   */
  public function distance($location)
  {
    return Location::KMRADIUS * acos(
      cos(deg2rad($this->latitude))
        * cos(deg2rad($location->latitude))
        * cos(deg2rad($location->longitude) - deg2rad($this->longitude))
        + sin(deg2rad($this->latitude))
        * sin(deg2rad($location->latitude))
    );
  }

  /**
   * 指定した座標への移動時間を返す
   * 
   * @param Location $location
   * @param float $speed 歩行速度(km/h)
   * @return float 移動時間(min)
   */
  public function travelTime($location, $speed = 4.2)
  {
    return $this->Distance($location) * $speed * 60;
  }
}
