<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserHasRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // alias middleware kustom
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);

        // kalau perlu, kamu bisa menambahkan ke groups:
        // $middleware->group('admin', ['auth', 'role:admin']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();