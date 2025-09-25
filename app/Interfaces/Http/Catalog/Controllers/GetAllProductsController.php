<?php

namespace App\Interfaces\Http\Catalog\Controllers;

use Illuminate\Http\JsonResponse;
use Application\Catalog\Services\GetAllProductsService;

final class GetAllProductsController
{
    public function __construct(
        private GetAllProductsService $service
    ) {}

    public function __invoke(): JsonResponse
    {
        $products = $this->service->execute();
        return response()->json($products);
    }
}
