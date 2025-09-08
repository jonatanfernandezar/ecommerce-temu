<?php

namespace Application\Seller\DTO;

use App\Domain\Catalog\Entities\Product;

final class SellerProductDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly string $categoryId
    ) {}

    public static function fromDomain(Product $product): self
    {
        return new self(
            $product->getId(),
            $product->getName(),
            $product->getDescription(),
            $product->getPrice()->amount(),
            $product->getStock(),
            $product->getCategoryId()
        );
    }
}
