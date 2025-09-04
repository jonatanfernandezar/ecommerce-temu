<?php

namespace Domain\Seller\Events;

use Domain\Ordering\Entities\Order;

class SellerOrderShipped
{
    public function __construct(
        public readonly Order $order
    ) {}
}
