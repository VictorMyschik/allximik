<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Telegram\TelegramService;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testMy(): void
    {
        //$content = $this->getContent();

        $content = file_get_contents(__DIR__ . '/maxon.txt');

        $crawler = new Crawler($content);

        $scriptContent = $crawler->filter('script')->each(function (Crawler $node) {
            if (strpos($node->text(), 'new ListViewModel') !== false) {
                return $node->text();
            }
            return null;
        });

        $scriptContent = array_filter($scriptContent);

        if (!empty($scriptContent)) {
            $scriptContent = reset($scriptContent);
            $startCh = strpos($scriptContent, 'new ListViewModel(');
            $endCh = strpos($scriptContent, 'ko.applyBindings(');
            $rawContent = substr($scriptContent, $startCh + 18, $endCh - $startCh - 21);
            $content = json_decode($rawContent, true)['PreloadedData']['Items'];
        }
    }

    private function getContent(): string
    {
        $client = new Client(['cookies' => true]);
        $url = 'https://www.maxon.pl/mieszkania/oferty/mieszkania/wynajem?PriceTotalPLNFrom=&PriceTotalPLNTo=3500&AreaFrom=50&AreaTo=&RoomsCountFrom=3&RoomsCountTo=3';

        $response = $client->get(
            $url,
            [
                'headers' => [
                    'Accept'          => '*/*',
                    'Connection'      => 'keep-alive',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Host'            => 'www.maxon.pl',
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',

                ],
                'cookies' => new CookieJar(),
            ]
        );

        return $response->getBody();
    }

    public function testImport(): void
    {
        /** @var TelegramService $service */
        $service = app(TelegramService::class);
        $service->sendMessage(1, []);
    }
}
