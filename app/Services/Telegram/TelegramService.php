<?php

declare(strict_types=1);

namespace App\Services\Telegram;


use App\Models\Offer;

final readonly class TelegramService
{
    public function __construct(
        private ClientInterface          $client,
        private OfferRepositoryInterface $offerRepository
    ) {}


    public function sendMessage(int $offerId): void
    {
        $offer = $this->offerRepository->getOfferById($offerId);
        $message = $this->buildMessage($offer);

        $this->client->sendMessage(
            $offer->getLink()->getTgUserId(),
            $message
        );
    }

    private function buildMessage(Offer $offer): string
    {
        $data = json_decode($offer->getSl(), true, 512, JSON_THROW_ON_ERROR);

        $rows['Title:'] = html_entity_decode($data['title']);
        $rows['Price:'] = html_entity_decode($data['price']['displayValue']);

        foreach ($data['params'] as $param) {
            if (in_array($param['key'], ['floor_select', 'm', 'rooms'], true)) {
                $rows[$param['name']] = html_entity_decode($param['value']);
            }
        }

        $src = str_replace('\u002F', '/', $data['photos'][0]);
        $src = str_replace('\u002F', '/', $src);
        //$rows['photo'] = $src;

        $url = str_replace('\u002F', '/', $data['url']);
        $url = str_replace('\u002F', '/', $url);
        $rows['URL:'] = $url;

        $out = '';
        foreach ($rows as $key => $item) {
            $out .= $key . ': ' . $item . "\n";
        }

        return $out;
    }
}
