<?php

namespace Domain\Ordering\Events;

use Domain\Ordering\Entities\Order;

final class OrderPaid
{
    public function __construct(public readonly Order $order) {}
}
