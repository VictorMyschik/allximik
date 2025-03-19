<?php

namespace App\Services\Telegram\Api;

use App\Services\Telegram\ClientInterface;

class Client implements ClientInterface
{
    private const string TG_HOST = 'https://api.telegram.org/bot';

    public function sendMessage(string $userId, string $message): void
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL            => self::TG_HOST . env('TELEGRAM_TOKEN') . '/sendMessage',
                CURLOPT_POST           => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_POSTFIELDS     => array(
                    'chat_id'    => $userId,
                    'text'       => $message,
                    'parse_mode' => 'HTML',
                ),
            )
        );
        curl_exec($ch);
    }

    public function setWebHook(): array
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL            => self::TG_HOST . env('TELEGRAM_TOKEN') . '/setWebhook?url=' . route('telegram.webhook'),
                CURLOPT_POST           => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT        => 10,
            ]
        );

        $response = curl_exec($ch);

        return json_decode($response, true);
    }
}
