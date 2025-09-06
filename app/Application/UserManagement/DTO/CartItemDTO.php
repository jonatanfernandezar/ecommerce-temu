<?php

namespace Application\UserManagement\DTO;

use Domain\UserManagement\Entities\CartItem as DomainCartItem;

final class CartItemDTO
{
    public function __construct(
        public readonly string $productId,
        public readonly int $quantity,
        public readonly float $unitPrice // unidades principales (ej. 19.99)
    ) {}

    public static function fromDomain(DomainCartItem $item): self
    {
        $money = $item->unitPrice(); // Domain\Shared\ValueObjects\Money
        return new self(
            $item->productId()->value(),
            $item->quantity()->value(),
            $money->amount() / 100
        );
    }
}
