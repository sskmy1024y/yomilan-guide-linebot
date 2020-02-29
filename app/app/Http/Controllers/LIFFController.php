<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LIFFController extends Controller
{
  public function index(Request $request)
  {
    $gameSessionID = $request->session_id;
    $view = "LIFF/index";
    switch ($request->method) {
      case 'decide':
        $view = "LIFF/decide";
        break;
      case 'showkeyword':
        $view = "LIFF/showkeyword";
        break;
      case 'result':
        $view = "LIFF/result";
        break;
    }
    return view($view, compact('gameSessionID'));
  }
}
