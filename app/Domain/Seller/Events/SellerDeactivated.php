<?php

namespace Domain\Seller\Events;

use Domain\Seller\ValueObjects\SellerId;

class SellerDeactivated
{
    public function __construct(public readonly SellerId $sellerId) {}
}
