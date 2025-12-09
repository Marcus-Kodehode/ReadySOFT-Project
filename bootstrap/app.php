<?php

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
        // Registrer middleware aliases for tilgangskontroll
        // 'subscription' - Sjekker om bruker har aktiv subscription (brukes på /dashboard/* ruter)
        // 'admin' - Sjekker om bruker har admin-rolle (brukes på /admin/* ruter)
        $middleware->alias([
            'subscription' => \App\Http\Middleware\CheckActiveSubscription::class,
            'admin' => \App\Http\Middleware\CheckAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
