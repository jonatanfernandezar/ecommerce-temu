<?php

namespace Domain\Seller\Events;

use Domain\Seller\Entities\SellerProduct;

class SellerProductAdded
{
    public function __construct(
        public readonly SellerProduct $product
    ) {}
}
