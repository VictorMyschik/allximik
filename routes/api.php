<?php

use App\Http\Controllers\Auth\AuthJWTController;
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;


/// Auth routes
Route::group(['prefix' => 'auth'], function () {
  Route::post('login', [AuthJWTController::class, 'login'])->name('api.login');
  Route::post('register', [AuthJWTController::class, 'register'])->name('api.register');
});

Route::group(['middleware' => 'auth.jwt', 'prefix' => 'auth'], function () {
  Route::post('logout', [AuthJWTController::class, 'logout'])->name('api.logout');
  Route::post('refresh', [AuthJWTController::class, 'refresh'])->name('api.refresh');
});

/// Public routes
Route::group([], function () {
  //Route::post('/travel/list', [TravelController::class, 'getList'])->name('api.travel.list');
});

/// Private routes
Route::group(['middleware' => 'auth.jwt'], function () {

});
