<?php

namespace App\Services\Course;

use App\Models\Facility;
use App\Models\Route;

class CourseGenerate
{
  private $current; // Current Location

  /**
   * コンストラクタの作成
   * 
   * @param array $current current location
   */
  public function __construct(array $current)
  {
    $this->current = $current;
  }

  public function generate()
  {
    $facility = Facility::all();
    var_dump($facility);
  }
}
