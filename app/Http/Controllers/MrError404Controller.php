<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class MrError404Controller extends Controller
{
    public function indexView(): View|Application|Factory
    {
        return View('404');
    }
}
