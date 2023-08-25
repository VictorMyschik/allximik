<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class MrFAQController extends Controller
{
  /**
   * Страница в админке. Управление.
   */
  public function adminFaqPage(): View|\Illuminate\Foundation\Application|Factory|Application
  {
    $out = array();

    return View('Admin.mir_admin_faq_page')->with($out);
  }

  public function faqPage(): View|\Illuminate\Foundation\Application|Factory|Application
  {
    $out = array();
    $out['list'] = Faq::all();

    return View('faq_page')->with($out);
  }
}
