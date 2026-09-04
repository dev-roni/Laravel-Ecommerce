<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Schedule command for Cleaning Idempotency key
Schedule::command('idempotency:cleanup')->dailyAt('06.00');

// schedule command for cleaning audit logs
Schedule::command('audit:cleanup --days=90')->monthly();