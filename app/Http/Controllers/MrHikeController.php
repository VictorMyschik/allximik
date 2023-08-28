<?php

namespace App\Http\Controllers;

use App\Models\Hike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MrHikeController extends Controller
{
  public function index(string $token): RedirectResponse
  {
    abort_if(!is_numeric($token), 404);

    /** @var Hike $hike */
    $hike = Hike::where('public_id', $token)->firstOrFail();

    if (Auth::guest()) {
      return redirect()->route('login')->withInput(['token' => $hike->getPublicId()]);
    }

    return redirect()->route('hike.details', ['hike' => $hike->id()]);
  }
}
