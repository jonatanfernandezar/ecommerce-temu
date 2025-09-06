<?php

namespace Application\UserManagement\Commands;

use Domain\Shared\ValueObjects\Address;

final class CheckoutCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly Address $shippingAddress // << agregado
    ) {}
}
