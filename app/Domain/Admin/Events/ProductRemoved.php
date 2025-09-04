<?php

namespace Domain\Admin\Events;

use Domain\Catalog\ValueObjects\ProductId;

class ProductRemoved
{
    private ProductId $productId;

    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
