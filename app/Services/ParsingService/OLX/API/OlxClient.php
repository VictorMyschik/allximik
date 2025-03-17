<?php

declare(strict_types=1);

namespace App\Services\ParsingService\OLX\API;

use App\Services\ParsingService\OLX\OlxClientInterface;
use App\Services\Traits\LogTrait;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class OlxClient implements OlxClientInterface
{
    use LogTrait;

    private const string HOST = 'https://www.olx.pl';

    public function __construct(
        private ClientInterface $client,
        private LoggerInterface $log,
    ) {}


    public function loadPage(string $path, string $query): string
    {
        return $this->send(httpMethod: 'GET', url: $path . '?' . $query, request: null, method: __FUNCTION__);
    }

    private function send(string $httpMethod, string $url, mixed $request, string $method): string
    {
        $requestId = Uuid::v4()->toRfc4122();
        $headers = $this->buildHeaders();
        $payload = $request ? json_encode($request) : null;
        $this->logRequest($requestId, $payload, $method, $url, $headers);
        $time = microtime(true);

        try {
            $httpResponse = $this->client->send(
                request: new Request($httpMethod, self::HOST . $url),
                options: ['body' => $payload, 'headers' => $headers],
            );
        } catch (\Throwable $e) {
            $this->logError($e, $requestId, $httpResponse ?? null, $method, $url);

            throw $e;
        }

        $time = (int)(microtime(true) - $time);
        $this->logResponse($requestId, (string)$httpResponse->getBody(), $method, $url, $time);

        return (string)$httpResponse->getBody();
    }

    private function buildHeaders(array $headers = []): array
    {
        return array_merge([
            'Content-Type' => 'application/json',
        ], $headers);
    }
}
