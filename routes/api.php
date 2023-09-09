<?php

use App\Http\Controllers\Auth\AuthJWTController;
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
  //Route::post('/hike/list', [AuthJWTController::class, 'logout'])->name('api.logout');
});

/// Private routes
Route::group(['middleware' => 'auth.jwt'], function () {

});
