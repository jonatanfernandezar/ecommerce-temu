<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\ViewOrderHistoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class OrderController
{
    private ViewOrderHistoryService $service;

    public function __construct(ViewOrderHistoryService $service)
    {
        $this->service = $service;
    }

    public function history(Request $request): JsonResponse
    {
        $userId = new \Domain\UserManagement\ValueObjects\UserId($request->user()->id);
        $orders = $this->service->execute($userId);
        return response()->json($orders);
    }
}