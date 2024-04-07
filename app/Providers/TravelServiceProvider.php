<?php

namespace App\Providers;

use App\Classes\Travel\TravelClass;
use App\Classes\Validation\TravelValidation;
use App\Http\Middleware\AuthenticateAPIMiddleware;
use App\Repositories\Travel\TravelDBRepository;
use App\Repositories\Travel\TravelRepositoryCache;
use App\Services\Travel\TravelRepositoryInterface;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class TravelServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->app->singleton(TravelClass::class, function ($app) {
      return new TravelClass(Auth::guard(AuthenticateAPIMiddleware::GUARD)->user());
    });

    $this->app->singleton(TravelValidation::class, function () {
      return new TravelValidation(Auth::guard(AuthenticateAPIMiddleware::GUARD)->user());
    });

    $this->app->bind(TravelRepositoryInterface::class, function (Application $app) {
      return new TravelRepositoryCache(
        new TravelDBRepository($app->make(DatabaseManager::class)),
        $app->make(Repository::class)
      );
    });
  }
}
