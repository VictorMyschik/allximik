<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testMy(): void
    {
        $content = $this->getContent();

        //$content = file_get_contents('tests/Feature/olx.blade.php');

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
                if (strpos($item, '__PRERENDERED_STATE__= ') !== false) {
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


    }

    private function getContent(): string
    {
        $client = new Client(['cookies' => true]);
        $url = 'https://www.otodom.pl/pl/wyniki/sprzedaz/mieszkanie/mazowieckie/warszawa/warszawa/warszawa?distanceRadius=5&viewType=listing';

        $response = $client->get(
            $url,
            [
                'headers' => [
                    'Accept'          => '*/*',
                    'Connection'      => 'keep-alive',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Host'            => 'www.olx.pl',
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',

                ],
                'cookies' => new CookieJar(),
            ]
        );

        return $response->getBody();
    }
}
