<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\UserLink;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testParse(): void
    {
        $user = '488545536';

        $ids = DB::table(UserLink::getTableName() . ' as ul1')
            ->join(UserLink::getTableName() . ' as ul2', 'ul1.link_id', '=', 'ul2.link_id')
            ->where('ul1.user', $user)
            ->groupBy('ul1.link_id')
            ->havingRaw('COUNT(ul2.link_id) = 1')
            ->pluck('ul1.link_id')
            ->toArray();

        DB::table(Link::getTableName())->whereIn('id', $ids)->delete();
        DB::table(UserLink::getTableName())->where('user', $user)->delete();
    }
}
