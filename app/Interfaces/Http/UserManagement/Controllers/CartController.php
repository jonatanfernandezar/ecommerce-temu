<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\ViewCartService;
use Application\UserManagement\Services\UpdateCartService;
use Application\UserManagement\Services\ClearCartService;
use Application\UserManagement\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class CartController
{
    private ViewCartService $viewService;
    private UpdateCartService $updateService;
    private ClearCartService $clearService;
    private CheckoutService $checkoutService;

    public function __construct(
        ViewCartService $viewService,
        UpdateCartService $updateService,
        ClearCartService $clearService,
        CheckoutService $checkoutService
    ) {
        $this->viewService     = $viewService;
        $this->updateService   = $updateService;
        $this->clearService    = $clearService;
        $this->checkoutService = $checkoutService;
    }

    public function view(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $cartDTO = $this->viewService->execute($userId);

        return response()->json($cartDTO);
    }

    public function update(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'product_id' => 'required|uuid',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cartDTO = $this->updateService->execute($userId, $data['product_id'], $data['quantity']);

        return response()->json($cartDTO);
    }

    public function clear(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $this->clearService->execute($userId);

        return response()->json(['message' => 'Cart cleared']);
    }

    public function checkout(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'payment_method' => 'required|string|in:card,paypal,simulated',
        ]);

        $orderDTO = $this->checkoutService->execute($userId, $data['payment_method']);

        return response()->json($orderDTO, 201);
    }
}