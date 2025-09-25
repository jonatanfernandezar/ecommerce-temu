<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Log;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Registrar el alias jwt manualmente
        $this->app['router']->aliasMiddleware('jwt', \App\Interfaces\Http\UserManagement\Middleware\JwtMiddleware::class);
    }
}
