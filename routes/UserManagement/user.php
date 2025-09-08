<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\UserManagement\Controllers\RegisterUserController;
use App\Interfaces\Http\UserManagement\Controllers\LoginUserController;
use App\Interfaces\Http\UserManagement\Controllers\UserProfileController;
use App\Interfaces\Http\UserManagement\Controllers\CartController;
use App\Interfaces\Http\UserManagement\Controllers\OrderController;

Route::prefix('users')->group(function () {
    // Registro y login
    Route::post('register', [RegisterUserController::class, '__invoke']);
    Route::post('login', [LoginUserController::class, '__invoke']);

    // Perfil de usuario
    Route::get('profile', [UserProfileController::class, 'show']);
    Route::put('profile', [UserProfileController::class, 'update']);

    // Carrito
    Route::get('cart', [CartController::class, 'view']);
    Route::post('cart', [CartController::class, 'update']);
    Route::delete('cart/clear', [CartController::class, 'clear']);
    Route::post('checkout', [CartController::class, 'checkout']);

    // Órdenes
    Route::get('orders', [OrderController::class, 'history']);
});
