<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Sites\Realting;

use App\Models\Link;
use App\Services\ParsingService\DTO\OfferDto;
use App\Services\ParsingService\ParsingStrategyInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

final readonly class RealtingParseService implements ParsingStrategyInterface
{
    public function __construct(
        private RealtingClientInterface $client,
        private LoggerInterface         $logger,
    ) {}

    public function parse(Link $link): array
    {
        parse_str($link->getQuery(), $parameters);

        $parsedOut = [];

        for ($page = 1; $page <= 1000; $page++) {
            try {
                $data = $this->loadPage($link->getPath(), $parameters, $page);
                $parsed = $this->parseData($data);

                if (empty($parsed)) {
                    break;
                }

                $parsedOut = array_merge($parsed, $parsedOut);
            } catch (\Throwable $e) {
                if ($e->getCode() === 404) {
                    break;
                }
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
                offerId: (int)$item['id'],
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

        $scriptContent = $crawler->filter('div.row.with-mb.sm-gutters');

        $content = [];

        $scriptContent->filter('.teaser-tile')->each(function (Crawler $node) use (&$content) {
            $item = [
                'title' => $node->filter('.teaser-title')->text(),
                'price' => $node->filter('.price-item')->text(),
                'link'  => $node->filter('a')->attr('href'),
                'id'    => $node->attr('data-id'),
            ];

            try {
                $item['rooms'] = $node->filter('.unit-item')->eq(0)->filter('span')->text();
                $item['area'] = $node->filter('.unit-item')->eq(1)->filter('span')->text();
            } catch (\Exception $e) {
                $item['rooms'] = '';
                $item['area'] = '';
            }

            $content[] = $item;
        });

        return $content;
    }
}
