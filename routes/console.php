<?php

use App\Console\Commands\ExpireStaleOperationsCommand;
use App\Console\Commands\FinalizeCashierCommissionCommand;
use App\Console\Commands\GenerateUpcomingInvoicesCommand;
use App\Console\Commands\ProcessSubscriptionLifecycleCommand;
use App\Console\Commands\SendCashierCommissionReminderCommand;
use App\Jobs\CleanupOldExportFilesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(ExpireStaleOperationsCommand::class)->everyMinute();
Schedule::command(ProcessSubscriptionLifecycleCommand::class)->dailyAt('00:05');
Schedule::command(GenerateUpcomingInvoicesCommand::class)->dailyAt('00:10');
Schedule::command(SendCashierCommissionReminderCommand::class)->dailyAt('09:00');
Schedule::command(FinalizeCashierCommissionCommand::class)->dailyAt('00:01');
Schedule::job(new CleanupOldExportFilesJob(30))->dailyAt('03:15');
