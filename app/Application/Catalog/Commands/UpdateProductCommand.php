<?php

namespace Application\Catalog\Commands;

final class UpdateProductCommand
{
    /**
     * @param array<string, mixed>|null $attributes
    */
    public function __construct(
        public readonly string $productId,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stock = null,
        public readonly ?string $categoryId = null,
        public readonly ?string $brandId = null,
        public readonly ?array $attributes = null
    ) {}
}
