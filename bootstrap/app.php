<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        then: function () {
            // Todas las rutas de módulos estarán bajo /api
            Route::prefix('api')
                ->middleware('api')
                ->group(function () {
                    require base_path('routes/UserManagement/user.php');
                    require base_path('routes/Admin/admin.php');
                    require base_path('routes/Seller/seller.php');
                    require base_path('routes/Ordering/ordering.php');
                    require base_path('routes/Catalog/catalog.php');
                });
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
