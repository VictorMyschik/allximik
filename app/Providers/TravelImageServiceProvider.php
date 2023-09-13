<?php

namespace App\Providers;

use App\Classes\Travel\Image\ImageClass;
use App\Classes\ValidationClass;
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

    $this->app->singleton(ValidationClass::class, function () {
      return new ValidationClass(Auth::guard(AuthenticateAPIMiddleware::GUARD)->user());
    });
  }
}
