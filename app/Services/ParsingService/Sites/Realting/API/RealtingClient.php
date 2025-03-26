<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Sites\Realting\API;

use App\Services\ParsingService\Sites\Realting\RealtingClientInterface;
use App\Services\Traits\LogTrait;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class RealtingClient implements RealtingClientInterface
{
    use LogTrait;

    private const string HOST = 'https://realting.com';

    public function __construct(
        private ClientInterface $client,
        private LoggerInterface $log,
    ) {}


    public function loadPage(string $path, array $query): string
    {
        $query = http_build_query($query);
        $path = sprintf('%s?%s', $path, $query);

        return $this->send(httpMethod: 'GET', path: $path, request: null, method: __FUNCTION__);
    }

    private function send(string $httpMethod, string $path, mixed $request, string $method): string
    {
        $requestId = Uuid::v4()->toRfc4122();
        $headers = $this->buildHeaders();
        $payload = $request ? json_encode($request) : null;
        $this->logRequest($requestId, $payload, $method, $path, $headers);
        $time = microtime(true);

        try {
            $httpResponse = $this->client->send(
                request: new Request($httpMethod, self::HOST . $path),
                options: ['body' => $payload, 'headers' => $headers],
            );
        } catch (\Throwable $e) {
            $this->logError($e, $requestId, $httpResponse ?? null, $method, $path);

            throw $e;
        }

        $time = (int)(microtime(true) - $time);
        //$this->logResponse($requestId, (string)$httpResponse->getBody(), $method, $path, $time);

        return (string)$httpResponse->getBody();
    }

    private function buildHeaders(array $headers = []): array
    {
        return array_merge([
            'Content-Type' => 'application/json',
        ], $headers);
    }
}
