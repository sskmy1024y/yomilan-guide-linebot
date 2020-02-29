<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityType;
use Illuminate\Http\Request;

class LIFFController extends Controller
{
  public function index(Request $request)
  {
    $facilities = Facility::where('type', '=', FacilityType::ATTRACTION)->inRandomOrder()->limit(5)->get();

    $view = "pages/liff";
    return view($view, compact('facilities'));
  }
}
