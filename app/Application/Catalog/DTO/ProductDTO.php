<?php

namespace Application\Catalog\DTO;

use App\Domain\Catalog\Entities\Product; // ajusta si tu entidad usa otro namespace
use App\Domain\Catalog\ValueObjects\Price;

final class ProductDTO
{
    /**
     * @param array<string, mixed> $attributes
    */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly ?string $categoryId = null,
        public readonly ?string $brandId = null,
        public readonly array $attributes = []
    ) {}

    public static function fromDomain(Product $p): self
    {
        // convertir Price VO a float de manera explícita
        $price = $p->getPrice()->amount();

        return new self(
            $p->getId(),
            $p->getName(),
            $p->getDescription(),
            $price,
            $p->getStock(),
            $p->getCategoryId(),
            $p->getBrandId(),
            $p->getAttributes()
        );
    }
}