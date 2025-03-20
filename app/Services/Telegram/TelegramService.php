<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Models\Offer;
use App\Services\OfferRepositoryInterface;

final readonly class TelegramService
{
    public function __construct(
        private ClientInterface          $client,
        private OfferRepositoryInterface $offerRepository,
    ) {}

    public function sendMessage(int $offerId, array $userIds): void
    {
        $offer = $this->offerRepository->getOfferById($offerId);
        $message = $this->buildMessage($offer->getSl(), $offer);

        foreach ($userIds as $userId) {
            $this->client->sendMessage($userId, $message);
        }
    }

    private function buildMessage(string $jsonData, Offer $offer): string
    {
        $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);

        $extractor = new OfferExtractor($offer->getLink()->getType(), $data);

        $rows['Title:'] = $extractor->getTitle();
        $rows['Price:'] = $extractor->getPrice();

        foreach ($data['params'] as $param) {
            if (in_array($param['key'], ['floor_select', 'm', 'rooms'], true)) {
                $rows[$param['name']] = html_entity_decode($param['value']);
            }
        }

        $src = str_replace('\u002F', '/', $data['photos'][0]);
        $src = str_replace('\u002F', '/', $src);
        //$rows['photo'] = $src;

        $rows['URL:'] = $extractor->getLink();

        $out = '';
        foreach ($rows as $key => $item) {
            $out .= $key . ': ' . $item . "\n";
        }

        return $out;
    }
}
