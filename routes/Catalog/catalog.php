<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Catalog\Controllers\SearchProductsController;
use App\Interfaces\Http\Catalog\Controllers\GetAllProductsController as ListProductsController;
use App\Interfaces\Http\Catalog\Controllers\GetProductByIdController;
use App\Interfaces\Http\Catalog\Controllers\CreateProductController;
use App\Interfaces\Http\Catalog\Controllers\UpdateProductController;
use App\Interfaces\Http\Catalog\Controllers\DeleteProductController;

Route::prefix('catalog')->group(function () {
    // 🔓 Público (usuarios normales pueden acceder)
    Route::get('products/search', SearchProductsController::class); // /api/catalog/products/search?q=iphone
    Route::get('products', ListProductsController::class); // listado general

    // 🔒 Solo Admin y Seller
    Route::middleware(['jwt', 'role:admin,seller'])->group(function () {
        Route::get('products/{id}', GetProductByIdController::class);
        Route::post('products', CreateProductController::class);
        Route::put('products/{id}', UpdateProductController::class);
        Route::delete('products/{id}', DeleteProductController::class);
    });
});
