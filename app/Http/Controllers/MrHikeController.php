<?php

namespace App\Http\Controllers;

use App\Models\Hike;
use Illuminate\Http\RedirectResponse;

class MrHikeController extends Controller
{
  public function index(string $token): RedirectResponse
  {
    abort_if(!is_numeric($token), 404);

    $hike = Hike::where('public_id', $token)->firstOrFail();

    return redirect()->route('hike.details', ['hike' => $hike->id()]);
  }
}
