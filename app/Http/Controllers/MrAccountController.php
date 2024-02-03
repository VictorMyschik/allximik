<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MrAccountController extends Controller
{
  public function index(): View
  {
    $out['page_title'] = 'Account';

    return View('account.main_account_page')->with($out);
  }
}
