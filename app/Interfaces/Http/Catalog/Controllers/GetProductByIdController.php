<?php

namespace App\Interfaces\Http\Catalog\Controllers;

use Illuminate\Http\JsonResponse;
use Application\Catalog\Services\GetProductByIdService;

final class GetProductByIdController
{
    public function __construct(
        private GetProductByIdService $service
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $productDTO = $this->service->execute($id);

        if (!$productDTO) {
            return response()->json(['error' => '❌ Producto no encontrado'], 404);
        }

        return response()->json($productDTO);
    }
}
