<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\UserLink;
use App\Services\ParsingService\OLX\OlxClientInterface;
use App\Services\ParsingService\OLX\OlxParseService;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMy(): void
    {
        $service = new OlxParseService(
            client: app(OlxClientInterface::class),
            logger: app(LoggerInterface::class),
        );
        $service->parse(Link::loadByOrDie(3));
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
