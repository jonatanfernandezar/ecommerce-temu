<?php

namespace Application\UserManagement\DTO;

use Domain\Ordering\Entities\OrderItem;

final class OrderItemDTO
{
    public function __construct(
        public readonly string $itemId,
        public readonly string $productId,
        public readonly int $quantity,
        public readonly float $unitPrice,
        public readonly float $lineTotal
    ) {}

    public static function fromDomain(OrderItem $item): self
    {
        return new self(
            $item->id()->value(),
            $item->productId()->value(),
            $item->quantity()->value(),
            $item->unitPrice()->amount(),
            $item->lineTotal()->amount()
        );
    }
}
