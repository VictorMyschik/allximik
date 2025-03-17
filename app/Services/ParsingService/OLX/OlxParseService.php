<?php

declare(strict_types=1);

namespace App\Services\ParsingService\OLX;

use App\Models\Link;
use Symfony\Component\DomCrawler\Crawler;

final readonly class OlxParseService
{
    public function __construct(
        private OlxRepositoryInterface $repository,
        private OlxClientInterface     $client,
    ) {}

    public function parse(Link $link): void
    {
        parse_str($link->getQuery(), $parameters);

        for ($page = 1; $page <= 10; $page++) {
            try {
                $data = $this->loadPage($link->getPath(), $parameters, $page);
                $parsed = $this->parseData($data);
                $this->saveParsedData($link->id(), $parsed);

                if ($page >= (int)$parsed['totalPages']) {
                    break;
                }
            } catch (\Throwable $e) {
                break;
            }
        }
    }

    private function loadPage(string $path, array $parameters, int $page): string
    {
        $query = $this->buildQuery($parameters, $page);

        return $this->client->loadPage($path, $query);
    }

    private function parseData(string $content): array
    {
        $crawler = new Crawler($content);

        $scriptContent = $crawler->filter('script')->each(function (Crawler $node) {
            if (strpos($node->text(), 'window.__PRERENDERED_STATE__= ') !== false) {
                return $node->text();
            }
            return null;
        });

        $scriptContent = array_filter($scriptContent);

        if (!empty($scriptContent)) {
            $scriptContent = reset($scriptContent);

            $result = explode('window.', $scriptContent);

            foreach ($result as $item) {
                if (str_contains($item, '__PRERENDERED_STATE__= ')) {
                    $scriptContent = $item;
                    break;
                }
            }

            $content = explode('__PRERENDERED_STATE__= "', $scriptContent);
            $content = str_replace('";', '', $content[1]);
            $content = str_replace('\"', '"', $content);
            $content = str_replace('\"', '"', $content);

            $content = json_decode($content, true)['listing']['listing'];
        }

        return $content;
    }

    private function saveParsedData(int $linkId, array $data): void
    {
        $existing = $this->repository->getOffersByLinkId($linkId);

        foreach ($data['ads'] as $item) {
            if (!array_key_exists($item['id'], $existing)) {
                $newId = $this->repository->saveOffer(
                    offerId: (string)$item['id'],
                    linkId: $linkId,
                    sl: json_encode($item),
                );


            }
        }
    }

    private function buildQuery(array $parameters, int $page): string
    {
        return http_build_query(array_merge($parameters, ['page' => $page]));
    }
}
