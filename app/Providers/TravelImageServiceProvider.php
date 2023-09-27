<?php

namespace App\Providers;

use App\Classes\Travel\Image\ImageClass;
use App\Classes\Validation\TravelImageValidation;
use App\Http\Middleware\AuthenticateAPIMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class TravelImageServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->singleton(ImageClass::class, function () {
      return new ImageClass(Auth::guard(AuthenticateAPIMiddleware::GUARD)->user());
    });

    $this->app->singleton(TravelImageValidation::class, function () {
      return new TravelImageValidation(Auth::guard(AuthenticateAPIMiddleware::GUARD)->user());
    });
  }
}
