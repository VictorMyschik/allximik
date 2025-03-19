<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ParsingService\OLX\OlxClientInterface;
use App\Services\ParsingService\ParserFactory\ParsingServiceFactory;
use App\Services\ParsingService\ParsingServiceFactoryInterface;
use Illuminate\Support\ServiceProvider;

class ParserFactoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ParsingServiceFactoryInterface::class, function () {
            return new ParsingServiceFactory(
                olxClient: app(OlxClientInterface::class),
                logger: app(\Psr\Log\LoggerInterface::class),
            );
        });
    }
}
