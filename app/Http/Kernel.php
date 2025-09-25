<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Middleware global (se ejecuta en todas las peticiones).
     */
    protected $middleware = [
        // Ejemplo: \App\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * Middleware asignables a grupos (web/api).
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // 👇 Ya no necesitamos $middlewareAliases en Laravel 11+
}
