<?php

namespace Domain\Catalog\Repositories;

use App\Domain\Catalog\Entities\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(string $id): ?Product;

    /**
     * @return Product[]
     */
    public function findAll(): array;
}
