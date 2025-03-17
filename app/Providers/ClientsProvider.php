<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ParsingService\OLX\API\OlxClient;
use App\Services\ParsingService\OLX\OlxClientInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ClientsProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OlxClientInterface::class, function () {
            return new OlxClient(
                client: new Client(),
                log: Log::channel(env('LOG_CHANNEL')),
            );
        });
    }
}
