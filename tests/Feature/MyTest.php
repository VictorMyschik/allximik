<?php

namespace Tests\Feature;

use App\Services\ImportService;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testParse(): void
    {
        /** @var ImportService $service */
        $service = app(ImportService::class);

        $link = 'https://www.olx.pl/nieruchomosci/mieszkania/sprzedaz/warszawa/?search%5Bfilter_enum_floor_select%5D%5B0%5D=floor_2&search%5Bfilter_enum_floor_select%5D%5B1%5D=floor_3&search%5Bfilter_enum_floor_select%5D%5B2%5D=floor_6&search%5Bfilter_enum_floor_select%5D%5B3%5D=floor_7&search%5Bfilter_enum_floor_select%5D%5B4%5D=floor_8&search%5Bfilter_enum_floor_select%5D%5B5%5D=floor_9&search%5Bfilter_enum_floor_select%5D%5B6%5D=floor_4&search%5Bfilter_enum_floor_select%5D%5B7%5D=floor_10&search%5Bfilter_enum_floor_select%5D%5B8%5D=floor_11&search%5Bfilter_enum_floor_select%5D%5B9%5D=floor_5&search%5Bfilter_enum_rooms%5D%5B0%5D=three&search%5Bfilter_float_m%3Afrom%5D=60&search%5Bfilter_float_price%3Ato%5D=800000';
        $service->import($link, '2313213');
    }
}
