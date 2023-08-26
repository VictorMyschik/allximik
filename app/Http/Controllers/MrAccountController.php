<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class MrAccountController extends Controller
{
  public function index(): View|Application|Factory
  {
    $out['page_title'] = 'Account';

    return View('account.main_account_page')->with($out);
  }
}
