<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ParseLinkJob;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

final readonly class ImportService
{
    public function __construct(
        private LinkRepositoryInterface $linkRepository,
        private LoggerInterface         $logger,
    ) {}

    public function import(string $rawUrl, string $user): void
    {
        try {
            $url = parse_url($rawUrl);
            if ($url === false || !isset($url['host'], $url['path'])) {
                throw new InvalidArgumentException('Invalid URL');
            }

            $type = SiteType::from($url['host']);
        } catch (InvalidArgumentException $exception) {
            $msg = 'Unsupported site or link is invalid: ' . $exception->getMessage();
            $this->logger->error($msg);
            $this->logger->info('URL: ' . $rawUrl);

            throw new Exception($msg);
        }

        $hash = $this->getHash($url);

        $link = $this->linkRepository->getByHash($hash);

        if (!$link) {
            $newLinkId = $this->importLink($user, $type, $url['path'], $url['query'] ?? '', $hash);
            ParseLinkJob::dispatch($newLinkId, false);

            return;
        }

        $this->linkRepository->upsertUserLink($user, $link->id);
    }

    private function getHash(array $url): string
    {
        parse_str($url['query'] ?? '', $input);

        $parameters = $this->sortQueryParameters($input);
        $parameters['path'] = $url['path'];

        return md5(json_encode($parameters));
    }

    private static function sortQueryParameters(mixed $data): mixed
    {
        if (!is_array($data)) {
            return $data;
        }

        ksort($data, SORT_STRING | SORT_NUMERIC);

        foreach ($data as $index => $val) {
            $data[$index] = self::sortQueryParameters($val);
        }

        return $data;
    }

    private function importLink(string $user, SiteType $type, string $path, string $query, string $hash): int
    {
        $linkId = $this->linkRepository->createLink($type, $path, $query, $hash);

        $this->linkRepository->upsertUserLink($user, $linkId);

        return $linkId;
    }
}
