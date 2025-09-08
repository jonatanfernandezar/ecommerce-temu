<?php

namespace Domain\Catalog\Repositories;

use App\Domain\Catalog\Entities\Product;
use Domain\Catalog\ValueObjects\ProductId;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(ProductId $id): ?Product;

    /**
     * @return Product[]
     */
    public function findAll(): array;
}
