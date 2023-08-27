<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class MrHikeInviteController
{
  public function index(string $token, string $status): View|Application|Factory
  {


    return view('hike', ['token' => $token]);
  }
}
