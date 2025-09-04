<?php

namespace Domain\Seller\Events;

use Domain\Seller\ValueObjects\SellerId;

class SellerProfileUpdated
{
    public function __construct(public readonly SellerId $sellerId) {}
}
