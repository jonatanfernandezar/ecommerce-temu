<?php

namespace Domain\Seller\Events;

use Domain\Seller\Entities\SellerProduct;

class SellerProductUpdated
{
    public function __construct(
        public readonly SellerProduct $product
    ) {}
}
