<?php

namespace Domain\Seller\Entities;

use Domain\Seller\ValueObjects\SellerId;
use Domain\Catalog\ValueObjects\ProductId;

class SellerProduct
{
    private SellerId $sellerId;
    private ProductId $productId;

    public function __construct(SellerId $sellerId, ProductId $productId)
    {
        $this->sellerId  = $sellerId;
        $this->productId = $productId;
    }

    public function sellerId(): SellerId { return $this->sellerId; }
    public function productId(): ProductId { return $this->productId; }
}
