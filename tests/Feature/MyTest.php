<?php

namespace Tests\Feature;

use App\Models\UserLink;
use App\Services\ImportService;
use App\Services\ParsingService\LinkRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMy(): void
    {
        $service = new ImportService(
            linkRepository: app(LinkRepositoryInterface::class),
            logger: app(LoggerInterface::class),
        );
        $url = 'https://www.olx.pl/nieruchomosci/mieszkania/sprzedaz/warszawa/?search%5Bfilter_float_price:to%5D=800000&search%5Bfilter_float_m:from%5D=60&search%5Bfilter_enum_rooms%5D%5B0%5D=three';
        $user = '4881545536';
        $service->import(url: $url, user: $user);
    }

    public function testParse(): void
    {
        $result = DB::table(UserLink::getTableName())
            ->where('user', '488545536')
            ->pluck('link_id')->toArray();

        $r = DB::table(UserLink::getTableName())
            ->whereIn('link_id', $result)
            ->groupBy('link_id')
            ->havingRaw('COUNT(*)=1')
            ->delete();
    }
}
