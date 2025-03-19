<?php

declare(strict_types=1);

namespace App\Services\ParsingService\OLX;

use App\Models\Link;
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

        for ($page = 1; $page <= 10; $page++) {
            try {
                $data = $this->loadPage($link->getPath(), $parameters, $page);
                $parsed = $this->parseData($data);
                $parsedOut = array_merge($parsed['ads'], $parsedOut);

                if ($page >= (int)$parsed['totalPages']) {
                    break;
                }
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());

                throw $e;
            }
        }

        return $parsedOut;
    }

    private function loadPage(string $path, array $parameters, int $page): string
    {
        $queryParameters = array_merge($parameters, ['page' => $page]);

        return $this->client->loadPage($path, $queryParameters);
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
}
