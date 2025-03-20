<?php

use App\Services\ParsingService\RunnerService;
use App\Services\System\CronService;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    new CronService(app(RunnerService::class))->runAllActive();
})->name('runAllActive')->description('Run all active cron jobs');
