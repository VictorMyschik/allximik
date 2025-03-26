<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Sites\Maxon;

use App\Models\Link;
use App\Services\ParsingService\DTO\OfferDto;
use App\Services\ParsingService\ParsingStrategyInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

final readonly class MaxonParseService implements ParsingStrategyInterface
{
    public function __construct(
        private MaxonClientInterface $client,
        private LoggerInterface      $logger,
    ) {}

    public function parse(Link $link): array
    {
        parse_str($link->getQuery(), $parameters);

        $parsedOut = [];

        for ($page = 1; $page <= 10; $page++) {
            try {
                $data = $this->loadPage($link->getPath(), $parameters, $page);
                $parsed = $this->parseData($data);

                if (empty($parsed)) {
                    break;
                }

                $parsedOut = array_merge($parsed, $parsedOut);
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
                offerId: $item['Id'],
                linkId: $linkId,
                sl: json_encode($item),
            );
        }

        return $out;
    }

    private function loadPage(string $path, array $parameters, int $page): string
    {
        $queryParameters = array_merge($parameters, ['Page' => $page]);

        return $this->client->loadPage($path, $queryParameters);
    }

    public function parseData(string $rawContent): array
    {
        $crawler = new Crawler($rawContent);

        $scriptContent = $crawler->filter('script')->each(function (Crawler $node) {
            if (strpos($node->text(), 'new ListViewModel') !== false) {
                return $node->text();
            }
            return null;
        });

        $scriptContent = array_filter($scriptContent);

        $content = [];

        if (!empty($scriptContent)) {
            $scriptContent = reset($scriptContent);
            $startCh = strpos($scriptContent, 'new ListViewModel(');
            $endCh = strpos($scriptContent, 'ko.applyBindings(');
            $rawContent = substr($scriptContent, $startCh + 18, $endCh - $startCh - 21);
            $content = json_decode($rawContent, true)['PreloadedData']['Items'];
        }

        return $content;
    }
}
