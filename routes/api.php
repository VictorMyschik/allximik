<?php

use App\Http\Controllers\Auth\AuthJWTController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
  Route::post('login', [AuthJWTController::class, 'login'])->name('api.login');
  Route::post('logout', [AuthJWTController::class, 'logout'])->name('api.logout');
  Route::post('refresh', [AuthJWTController::class, 'refresh'])->name('api.refresh');
  Route::post('register', [AuthJWTController::class, 'register'])->name('api.register');
});
