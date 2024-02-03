<?php

namespace App\Providers;

use App\Classes\SystemInfo\Cache\CacheRedisInfoClass;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CacheInfoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CacheRedisInfoClass::class, function (Application $app) {
            return new CacheRedisInfoClass($app->make(Repository::class)->connection()->client());
        });
    }
}
