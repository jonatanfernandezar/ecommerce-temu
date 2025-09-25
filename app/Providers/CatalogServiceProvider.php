<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Infrastructure\Catalog\Repositories\ProductRepository as PdoProductRepository;
use Domain\Catalog\Repositories\CategoryRepository as CategoryRepositoryInterface;
use Infrastructure\Catalog\Repositories\CategoryRepository as PdoCategoryRepository;
use Domain\Catalog\Repositories\BrandRepository;
use Infrastructure\Catalog\Repositories\BrandRepository as PdoBrandRepository;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
         // Bind de la interfaz a la implementación concreta
        $this->app->bind(ProductRepositoryInterface::class, PdoProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, PdoCategoryRepository::class);
        $this->app->bind(BrandRepository::class, PdoBrandRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
