<?php

namespace Domain\Seller\Repositories;

use Domain\Seller\Entities\SellerProduct;
use Domain\Seller\ValueObjects\SellerId;

interface SellerProductRepositoryInterface
{
    public function addProduct(SellerProduct $product): void;

    public function updateProduct(SellerProduct $product): void;

    public function removeProduct(SellerProduct $product): void;

    /**
     * @return SellerProduct[]
     */
    public function getProductsBySeller(SellerId $sellerId): array;
}
