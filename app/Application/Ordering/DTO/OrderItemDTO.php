<?php

namespace Application\Ordering\DTO;

final class OrderItemDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $productId,
        public readonly int $quantity,
        public readonly int $unitPrice, // cents
        public readonly int $lineTotal // cents
    ) {}
}
