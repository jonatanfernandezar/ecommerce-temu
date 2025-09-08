<?php

namespace Application\UserManagement\DTO;

use Domain\Ordering\Entities\Order;

final class OrderDTO
{
    /** @var OrderItemDTO[] */
    public array $items;

    /**
     * @param OrderItemDTO[] $items
    */

    public function __construct(
        public readonly string $orderId,
        public readonly string $userId,
        public readonly float $total,
        array $items
    ) {
        $this->items = $items;
    }

    public static function fromDomain(Order $order): self
    {
        $items = array_map(
            fn($item) => OrderItemDTO::fromDomain($item),
            $order->items()
        );

        return new self(
            $order->id()->value(),       // ✅ OrderId → string
            $order->buyerId()->value(),  // ✅ UserId → string
            $order->total()->amount(),   // ✅ Money → float
            $items
        );
    }
}
