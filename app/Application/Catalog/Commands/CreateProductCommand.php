<?php

namespace Application\Catalog\Commands;

use Domain\Catalog\ValueObjects\BrandId;
use Domain\Catalog\ValueObjects\CategoryId;

final class CreateProductCommand
{
    /**
     * @param array<string, mixed> $attributes
    */
    public function __construct(
        public readonly string $sellerId,
        public readonly ?string $productId, // null para nuevo producto
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,     // unidades principales (ej. 19.99)
        public readonly int $stock,
        public readonly CategoryId $categoryId,
        public readonly BrandId $brandId,
        public readonly array $attributes = [], // ['color' => 'red', 'size' => 'M']
        public readonly string $status // 'active', 'inactive', 'out_of_stock'
    ) {}
}
