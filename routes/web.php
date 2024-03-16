<?php

use App\Http\Controllers\Admin\AdminTravelController;
use App\Http\Controllers\MrAccountController;
use App\Http\Controllers\MrFAQController;
use App\Http\Controllers\MrTestController;
use App\Http\Controllers\MrWelcomeController;
use App\Http\Controllers\Travel\MrTravelInviteController;
use App\Http\Controllers\Travel\TravelController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Auth::routes();

Route::get('/logout', function () {
  Session::flush();
  Auth::logout();

  return redirect()->route('login');
})->name('logout');

// Clear cache
Route::get('/clear', function () {
  Artisan::call('cache:clear');
  Artisan::call('view:clear');
  Artisan::call('route:clear');
  Artisan::call('config:clear');

  return back();
})->name('clear');

Route::get('locale/{locale}', function ($locale) {
  Session::put('locale', $locale);
  return redirect()->back();
});

Route::get('/', [MrWelcomeController::class, 'index'])->name('welcome');

Route::get('/faq', [MrFAQController::class, 'faqPage'])->name('faq.page');
Route::post('/send-question', [MrFAQController::class, 'sendQuestion'])->name('faq.send.question');

Route::match(['get', 'post'], '/test', [MrTestController::class, 'index'])->name('test.page');
Route::match(['get', 'post'], '/travel/{token}', [TravelController::class, 'index'])->name('travel.public.link');
Route::match(['get', 'post'], '/travel/email-invite/{token}/{status}', [MrTravelInviteController::class, 'index'])->name('travel.email.invite.link');

Route::group(['middleware' => ['auth']], function () {
  Route::get('/account', [MrAccountController::class, 'index'])->name('account');
});

// Admin routes
Route::group(['middleware' => ['auth'], 'prefix' => 'admin/travel'], function () {
  Route::get('{travel_id}/image/show/{image_name}', [AdminTravelController::class, 'showImage'])->name('admin.show.image');
  Route::get('image/delete/{image_id}', [AdminTravelController::class, 'deleteImage'])->name('admin.delete.travel.image');
});

// User routes
Route::group(['middleware' => ['auth'], 'prefix' => 'account'], function () {
  Route::get('/travel/{travel_id}/page', [TravelController::class, 'index'])->name('account.travel.page');
});
