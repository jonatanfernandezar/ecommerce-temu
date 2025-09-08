<?php

namespace Application\Ordering\Commands;

/**
 * Command to change the status of an order.
 *
 * @param string $orderId OrderId value (UUID string)
 * @param string $newStatus One of the values from Domain\Ordering\ValueObjects\OrderStatus
 * @param string|null $performedBy optional UserId value performing the update
 */
final class UpdateOrderStatusCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $newStatus,
        public readonly ?string $performedBy = null
    ) {}
}
