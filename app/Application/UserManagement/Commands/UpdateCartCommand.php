<?php

namespace Application\UserManagement\Commands;

final class UpdateCartCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $productId,
        public readonly string $action, // "add" | "update" | "remove"
        public readonly ?int $quantity = null
    ) {}
}
