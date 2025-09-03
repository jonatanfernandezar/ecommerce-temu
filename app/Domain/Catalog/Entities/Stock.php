<?php

namespace Domain\Catalog\Entities;

use Domain\Catalog\ValueObjects\StockId;
use Domain\Catalog\ValueObjects\Quantity;
use Domain\Catalog\ValueObjects\ProductId;

class Stock
{
    private StockId $id;
    private ProductId $productId;
    private Quantity $quantity;

    public function __construct(StockId $id, ProductId $productId, Quantity $quantity)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function id(): StockId
    {
        return $this->id;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function addStock(int $amount): void
    {
        $this->quantity->add($amount);
    }

    public function removeStock(int $amount): void
    {
        $this->quantity->subtract($amount);
        // Podrías disparar StockLowEvent si quieres alertas
    }
}
