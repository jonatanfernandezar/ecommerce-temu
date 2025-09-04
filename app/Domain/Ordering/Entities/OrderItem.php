<?php

namespace Domain\Ordering\Entities;

use Domain\Ordering\ValueObjects\OrderItemId;
use Domain\Shared\ValueObjects\Money;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Catalog\ValueObjects\ProductId;

final class OrderItem
{
    private OrderItemId $id;
    private ProductId $productId;
    private Quantity $quantity;
    private Money $unitPrice;

    public function __construct(
        OrderItemId $id,
        ProductId $productId,
        Quantity $quantity,
        Money $unitPrice
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function id(): OrderItemId { return $this->id; }
    public function productId(): ProductId { return $this->productId; }
    public function quantity(): Quantity { return $this->quantity; }
    public function unitPrice(): Money { return $this->unitPrice; }

    public function lineTotal(): Money
    {
        return $this->unitPrice->multiply($this->quantity->value());
    }

    public function changeQuantity(Quantity $newQuantity): void
    {
        $this->quantity = $newQuantity;
    }
}
