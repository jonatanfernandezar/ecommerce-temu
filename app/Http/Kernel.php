<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Interfaces\Http\UserManagement\Middleware\JwtMiddleware;

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

    /**
     * Middleware individuales que se pueden usar en las rutas.
     */
    protected $routeMiddleware = [
        'jwt.auth' => \App\Interfaces\Http\UserManagement\Middleware\JwtMiddleware::class,
    ];
}
