<?php

namespace Application\Seller\Commands;

final class ManageSellerProductsCommand
{
    public function __construct(
        public readonly string $sellerId,
        public readonly string $action, // "create" | "update" | "delete"
        public readonly ?string $productId = null,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stock = null,
        public readonly ?string $categoryId = null
    ) {}
}
