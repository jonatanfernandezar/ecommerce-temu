<?php

namespace Domain\Catalog\Repositories;

use Domain\Catalog\Entities\Brand;
use Domain\Catalog\ValueObjects\BrandId;

interface BrandRepository
{
    public function save(Brand $brand): void;
    public function findById(BrandId $id): ?Brand;
    /** @return Brand[] */
    public function findAll(): array;
    public function delete(BrandId $id): void;
}
