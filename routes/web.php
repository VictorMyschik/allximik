<?php

use App\Http\Controllers\MrAccountController;
use App\Http\Controllers\MrFAQController;
use App\Http\Controllers\MrHikeController;
use App\Http\Controllers\MrHikeInviteController;
use App\Http\Controllers\MrTestController;
use App\Http\Controllers\MrWelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Auth::routes();

Route::get('/logout', function () {
  Session::flush();
  Auth::logout();

  return redirect()->route('login');
})->name('logout');

Route::get('/', [MrWelcomeController::class, 'index'])->name('welcome');

Route::match(['get', 'post'], '/faq', [MrFAQController::class, 'faqPage'])->name('faq.page');

Route::match(['get', 'post'], '/test', [MrTestController::class, 'index'])->name('test.page');
Route::match(['get', 'post'], '/hike/{token}', [MrHikeController::class, 'index'])->name('hike.public.link');
//Route::match(['get', 'post'], '/hike/email-invite/{token}/{status}', [MrHikeInviteController::class, 'index'])->name('hike.public.link');

Route::group(['middleware' => ['auth']], function () {
  Route::get('/account', [MrAccountController::class, 'index'])->name('account');
});
