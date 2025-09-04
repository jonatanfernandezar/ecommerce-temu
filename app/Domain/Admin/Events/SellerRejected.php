<?php

namespace Domain\Admin\Events;

use Domain\Seller\ValueObjects\SellerId;

class SellerRejected
{
    private SellerId $sellerId;

    public function __construct(SellerId $sellerId)
    {
        $this->sellerId = $sellerId;
    }

    public function sellerId(): SellerId
    {
        return $this->sellerId;
    }
}
