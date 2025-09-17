<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Infrastructure\UserManagement\Repositories\UserRepository as PdoUserRepository;
use Infrastructure\Eloquent\Repositories\UserRepositoryEloquent;

class UserManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if ($this->app->environment('testing')) {
            $this->app->bind(UserRepositoryInterface::class, UserRepositoryEloquent::class);
        } else {
            $this->app->bind(UserRepositoryInterface::class, PdoUserRepository::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
