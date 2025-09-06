<?php

namespace Domain\UserManagement\Events;

use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\OrderStatus;

final class OrderStatusChangedEvent
{
    private OrderId $orderId;
    private OrderStatus $newStatus;

    public function __construct(OrderId $orderId, OrderStatus $newStatus)
    {
        $this->orderId = $orderId;
        $this->newStatus = $newStatus;
    }

    public function orderId(): OrderId { return $this->orderId; }
    public function newStatus(): OrderStatus { return $this->newStatus; }
}
