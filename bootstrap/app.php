<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ✅ tambahkan ini
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\BasicAuthRoleMiddleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CorsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ daftarkan middleware alias di sini
        $middleware->alias([
            'basicauth' => BasicAuthMiddleware::class,
            'basicauth.role' => BasicAuthRoleMiddleware::class,
            'role' => CheckRole::class,
            'cors' => CorsMiddleware::class,
        ]);
        
        // ✅ tambahkan CORS middleware ke global middleware
        $middleware->append(CorsMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
