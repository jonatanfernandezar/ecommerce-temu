<?php

namespace Domain\UserManagement\Entities;

use Domain\Catalog\ValueObjects\ProductId;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Shared\ValueObjects\Money;

final class CartItem
{
    private ProductId $productId;
    private Quantity $quantity;
    private Money $unitPrice;

    public function __construct(ProductId $productId, Quantity $quantity, Money $unitPrice)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function changeQuantity(Quantity $newQuantity): void
    {
        $this->quantity = $newQuantity;
    }

    public function increaseQuantity(Quantity $by): void
    {
        $this->quantity = $this->quantity->add($by);
    }

    public function lineTotal(): Money
    {
        return $this->unitPrice->multiply($this->quantity->value());
    }
}
