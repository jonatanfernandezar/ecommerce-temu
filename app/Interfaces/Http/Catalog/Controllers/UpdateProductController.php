<?php

namespace App\Interfaces\Http\Catalog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Application\Catalog\Services\UpdateProductService;
use Application\Catalog\Commands\UpdateProductCommand;
use Domain\Catalog\ValueObjects\ProductId;

final class UpdateProductController
{
    public function __construct(
        private UpdateProductService $service
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0.01',
            'stock'       => 'nullable|integer|min:0',
        ]);

        $command = new UpdateProductCommand(
            new ProductId($id),
            $data['name']        ?? null,
            $data['description'] ?? null,
            $data['price']       ?? null,
            $data['stock']       ?? null
        );

        $updatedProduct = $this->service->execute($command);

        if ($updatedProduct === null) {
            return response()->json([
                'error' => 'Product not found or could not be updated.'
            ], 404);
        }

        return response()->json([
            'id'          => $updatedProduct->getId()->value(),
            'name'        => $updatedProduct->getName(),
            'description' => $updatedProduct->getDescription(),
            'price'       => $updatedProduct->getPrice()->amount(),
            'stock'       => $updatedProduct->getStock(),
            'category_id' => $updatedProduct->getCategoryId()->value(),
            'brand_id'    => $updatedProduct->getBrandId()?->value(),
            'status'      => $updatedProduct->status()->value(),
        ], 200);
    }
}
