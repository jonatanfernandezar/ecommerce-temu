<?php

namespace Application\Catalog\Commands;

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
        public readonly ?string $categoryId = null,
        public readonly ?string $brandId = null,
        public readonly array $attributes = [] // ['color' => 'red', 'size' => 'M']
    ) {}
}
