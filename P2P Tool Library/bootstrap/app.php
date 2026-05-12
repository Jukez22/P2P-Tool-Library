<?php

use App\Http\Middleware\CheckIfUserSuspended;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'suspended'     => CheckIfUserSuspended::class,
            'not.suspended' => CheckIfUserSuspended::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function ($schedule) {
        $schedule->command('app:expire-inventory-audits')->hourly();
        $schedule->command('app:process-late-returns')->daily();
        $schedule->command('app:refresh-dashboard-metrics')->everyFiveMinutes();
    })->create();
