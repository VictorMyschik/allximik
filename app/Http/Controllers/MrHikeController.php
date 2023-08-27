<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class MrHikeController extends Controller
{
  public function index(string $token): View|Application|Factory
  {
    return view('hike', ['token' => $token]);
  }
}
