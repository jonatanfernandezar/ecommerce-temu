<?php

namespace Domain\Catalog\Repositories;

use Domain\Catalog\Entities\Stock;
use Domain\Catalog\ValueObjects\StockId;

interface StockRepository
{
    public function save(Stock $stock): void;
    public function findById(StockId $id): ?Stock;
    /** @return Stock[] */
    public function findAll(): array;
    public function delete(StockId $id): void;
}
