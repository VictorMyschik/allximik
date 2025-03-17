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
       // $content = $this->getContent();

        $content = file_get_contents('tests/Feature/olx.blade.php');

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
            // Extract JSON From expl: window.__PRERENDERED_STATE__= "{"listing":{"listing":{"pageNumber"...

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
        $url = 'https://www.olx.pl/nieruchomosci/mieszkania/sprzedaz/warszawa/?page=2&search%5Bfilter_enum_floor_select%5D%5B0%5D=floor_2&search%5Bfilter_enum_floor_select%5D%5B1%5D=floor_3&search%5Bfilter_enum_floor_select%5D%5B2%5D=floor_6&search%5Bfilter_enum_floor_select%5D%5B3%5D=floor_7&search%5Bfilter_enum_floor_select%5D%5B4%5D=floor_8&search%5Bfilter_enum_floor_select%5D%5B5%5D=floor_9&search%5Bfilter_enum_floor_select%5D%5B6%5D=floor_4&search%5Bfilter_enum_floor_select%5D%5B7%5D=floor_10&search%5Bfilter_enum_floor_select%5D%5B8%5D=floor_11&search%5Bfilter_enum_floor_select%5D%5B9%5D=floor_5&search%5Bfilter_enum_rooms%5D%5B0%5D=three&search%5Bfilter_float_m%3Afrom%5D=60&search%5Bfilter_float_price%3Ato%5D=800000';

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
