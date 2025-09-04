<?php

namespace Domain\Seller\Events;

use Domain\Ordering\Entities\Order;

class SellerOrderAccepted
{
    public function __construct(
        public readonly Order $order
    ) {}
}
