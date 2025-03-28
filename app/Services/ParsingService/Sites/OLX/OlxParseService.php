<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Sites\OLX;

use App\Models\Link;
use App\Services\ParsingService\DTO\OfferDto;
use App\Services\ParsingService\ParsingStrategyInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

final readonly class OlxParseService implements ParsingStrategyInterface
{
    public function __construct(
        private OlxClientInterface $client,
        private LoggerInterface    $logger,
    ) {}

    public function parse(Link $link): array
    {
        parse_str($link->getQuery(), $parameters);

        $parsedOut = [];

        for ($page = 1; $page <= 100; $page++) {
            try {
                $data = $this->loadPage($link->getPath(), $parameters, $page);
                $parsed = $this->parseData($data);

                if (empty($parsed['ads'])) {
                    break;
                }

                $parsedOut = array_merge($parsed['ads'], $parsedOut);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());

                throw $e;
            }
        }

        return $this->convertToDto($link->id(), $parsedOut);
    }

    private function convertToDto(int $linkId, array $parsedOut): array
    {
        $out = [];

        foreach ($parsedOut as $item) {
            $out[] = new OfferDto(
                offerId: $item['id'],
                linkId: $linkId,
                sl: json_encode($item),
            );
        }

        return $out;
    }

    private function loadPage(string $path, array $parameters, int $page): string
    {
        $queryParameters = array_merge($parameters, ['page' => $page]);

        return $this->client->loadPage($path, $queryParameters);
    }

    public function parseData(string $rawContent): array
    {
        $crawler = new Crawler($rawContent);

        $scriptContent = $crawler->filter('script')->each(function (Crawler $node) {
            if (strpos($node->text(), 'window.__PRERENDERED_STATE__= ') !== false) {
                return $node->text();
            }
            return null;
        });

        $scriptContent = array_filter($scriptContent);

        if (!empty($scriptContent)) {
            $scriptContent = reset($scriptContent);

            $result = explode('window._', $scriptContent);

            foreach ($result as $item) {
                if (str_contains($item, '_PRERENDERED_STATE__= ')) {
                    $scriptContent = $item;
                    break;
                }
            }

            $content = explode('_PRERENDERED_STATE__= "', $scriptContent);
            $content = str_replace('";', '', $content[1]);
            $content = str_replace('\"', '"', $content);
            $content = str_replace('\"', '"', $content);

            $content = json_decode($content, true)['listing']['listing'];
        }

        return $content;
    }
}
