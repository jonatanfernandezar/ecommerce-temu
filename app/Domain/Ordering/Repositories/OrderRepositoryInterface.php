<?php

namespace Domain\Ordering\Repositories;

use Domain\Ordering\Entities\Order;
use Domain\Ordering\ValueObjects\OrderId;

interface OrderRepository
{
    public function save(Order $order): void;
    public function findById(OrderId $id): ?Order;
    /** @return Order[] */
    public function findByBuyer(string $buyerId): array; // buyerId = UserId->value()
    public function delete(OrderId $id): void;
}
