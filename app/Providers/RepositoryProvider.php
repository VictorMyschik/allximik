<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\LinkRepository;
use App\Repositories\OlxRepository;
use App\Services\ParsingService\LinkRepositoryInterface;
use App\Services\ParsingService\OLX\OlxRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LinkRepositoryInterface::class, function () {
            return new LinkRepository(
                db: $this->app['db']
            );
        });

        $this->app->bind(OlxRepositoryInterface::class, function () {
            return new OlxRepository(
                db: $this->app['db']
            );
        });
    }
}

