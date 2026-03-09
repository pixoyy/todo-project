<?php

use App\Http\Middleware\Authorize;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'access' => Authorize::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        // ✅ Update task status berdasarkan due date
        // Run setiap hari pada jam 00:00 (tengah malam) waktu Indonesia
        $schedule->command('task:update-status')
            ->dailyAt('00:00')
            ->timezone('Asia/Jakarta')
            ->name('update-task-status')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::error('❌ Task status update command failed at ' . now('Asia/Jakarta'));
            })
            ->onSuccess(function () {
                \Illuminate\Support\Facades\Log::info('✅ Task status update completed successfully at ' . now('Asia/Jakarta'));
            });

        // Tambahkan schedule lainnya di sini
        // $schedule->command('inspire')->hourly();
        // $schedule->command('cache:clear')->daily();
    })
    ->create();
