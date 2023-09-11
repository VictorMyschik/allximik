<?php

namespace App\Providers;

use App\Classes\Travel\TravelClass;
use App\Http\Middleware\AuthenticateAPIMiddleware;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class TravelServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->app->singleton(TravelClass::class, function ($app) {
      return new TravelClass(Auth::guard(AuthenticateAPIMiddleware::GUARD)->user());
    });
  }
}
