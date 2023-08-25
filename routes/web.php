<?php

use App\Http\Controllers\MrFAQController;
use App\Http\Controllers\MrTestController;
use App\Http\Controllers\MrWelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

Auth::routes();
Route::get('/logout', function () {
  Session::flush();
  Auth::logout();

  return redirect()->route('login');
})->name('logout');

Route::get('/', [MrWelcomeController::class, 'index'])->name('welcome');

Route::match(['get', 'post'], '/faq', [MrFAQController::class, 'faqPage'])->name('faq_page');

Route::match(['get', 'post'], '/test', [MrTestController::class, 'index'])->name('admin_test_page');

Route::group(['middleware' => ['auth']], function () {

});
