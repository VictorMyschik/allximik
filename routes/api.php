<?php

use App\Http\Controllers\Auth\AuthJWTController;
use App\Http\Controllers\Travel\TravelController;
use App\Http\Controllers\Travel\TravelImageController;
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

Route::group(['prefix' => 'travel'], function () {
    // Create Travel
    Route::post('create', [TravelController::class, 'create'])->name('api.travel.create');
    Route::post('update', [TravelController::class, 'update'])->name('api.travel.update');
    Route::post('details', [TravelController::class, 'details'])->name('api.travel.details');
    Route::post('delete', [TravelController::class, 'delete'])->name('api.travel.delete');
    Route::post('list', [TravelController::class, 'getList'])->name('api.travel.list');
    Route::post('personal/list', [TravelController::class, 'personalList'])->name('api.travel.personal.list');

    // Get list
    Route::post('image/list', [TravelImageController::class, 'getList'])->name('api.travel.image.list');
    // Upload image
    Route::post('image/upload', [TravelImageController::class, 'imageUpload'])->name('api.travel.image.upload');
    // Delete image
    Route::post('image/delete', [TravelImageController::class, 'deleteImage'])->name('api.travel.image.delete');
    // Public URL
    Route::get('{travel_id}/image/show/{image_name}', [TravelImageController::class, 'showImage'])->name('api.travel.image.get');
    // Update image description
    Route::post('image/update', [TravelImageController::class, 'updateImage'])->name('api.travel.image.update');
});
