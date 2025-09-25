<?php

namespace App\Interfaces\Http\Catalog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Application\Catalog\Services\CreateProductService;
use Application\Catalog\Commands\CreateProductCommand;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\BrandId;

final class CreateProductController
{
    public function __construct(
        private CreateProductService $service
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0.01',
            'stock'       => 'nullable|integer|min:0',
            'category_id' => 'nullable|uuid',
            'categoryId'  => 'nullable|uuid',
            'brand_id'    => 'nullable|uuid',
            'brandId'     => 'nullable|uuid',
            'attributes'  => 'nullable|array',
        ]);

        $command = new CreateProductCommand(
            sellerId: $request->user()->id,
            productId: null, // al crear es null
            name: $data['name'],
            description: $data['description'] ?? '',
            price: (float) $data['price'],
            stock: (int) ($data['stock'] ?? 0),
            categoryId: new CategoryId($data['category_id'] ?? $data['categoryId']),
            brandId: isset($data['brand_id']) || isset($data['brandId'])
                ? new BrandId($data['brand_id'] ?? $data['brandId'])
                : null,
            attributes: $data['attributes'] ?? [],
            status: 'active' // Por defecto al crear un producto es 'active'
        );

        $product = $this->service->execute($command);

        return response()->json([
            'id'          => $product->getId()->value(),
            'name'        => $product->getName(),
            'description' => $product->getDescription(),
            'price'       => $product->getPrice(),
            'stock'       => $product->getStock(),
            'category_id' => $product->getCategoryId(),
            'brand_id'    => $product->getBrandId(),
            'status'      => $product->status(),
        ], 201);
    }
}
