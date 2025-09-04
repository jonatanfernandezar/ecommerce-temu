<?php

namespace Domain\Seller\Events;

use Domain\Seller\Entities\SellerProduct;

class SellerProductRemoved
{
    public function __construct(
        public readonly SellerProduct $product
    ) {}
}
