<?php

namespace Domain\Admin\Events;

use Domain\Seller\ValueObjects\SellerId;

class SellerApproved
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
