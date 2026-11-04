<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $period = now()->format('Ym');

    Artisan::call('billing:generate-invoices', [
        'period' => $period,
    ]);
})
    ->name('billing.generate.invoices.monthly')
    ->monthlyOn(20, '01:00')
    ->timezone('America/El_Salvador')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('billing:apply-prepayments')
    ->monthlyOn(20, '03:00')
    ->timezone('America/El_Salvador')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('billing:cut-overdue-invoices')
    ->monthlyOn(10, '01:00')
    ->timezone('America/El_Salvador')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('mikrotik:sync-connections')
    ->everyMinute()
    ->timezone('America/El_Salvador')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('background:notify-expiring-contracts')
    ->dailyAt('08:10')
    ->timezone('America/El_Salvador')
    ->withoutOverlapping()
    ->onOneServer();
