<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Services\OfferRepositoryInterface;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\Telegram\Enum\OLXOfferParameters;

final readonly class TelegramService
{
    public function __construct(
        private ClientInterface          $client,
        private OfferRepositoryInterface $offerRepository,
    ) {}

    public function sendMessage(int $offerId, array $userIds): void
    {
        $offer = $this->offerRepository->getOfferById($offerId);
        $message = $this->buildMessage($offer->getSl(), $offer->getLink()->getType());

        foreach ($userIds as $userId) {
            $this->client->sendMessage($userId, $message);
        }
    }

    private function buildMessage(string $jsonData, SiteType $type): string
    {
        $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);

        $extractor = new OfferExtractor($type, $data);

        $rows['Title:'] = $extractor->getTitle();
        $rows['Price:'] = $extractor->getPrice();

        foreach ($this->getParameters($type) as $param) {
            $rows[$param->getLabel()] = $extractor->getParameter($param->value);
        }

        $rows['URL:'] = $extractor->getLink();

        $out = '';
        foreach ($rows as $key => $item) {
            $out .= $key . ': ' . $item . "\n";
        }

        return $out;
    }

    /**
     * @return OLXOfferParameters[]
     */
    private function getParameters(SiteType $type): array
    {
        return match ($type) {
            SiteType::OLX => OLXOfferParameters::getSelectList(),
        };
    }
}
