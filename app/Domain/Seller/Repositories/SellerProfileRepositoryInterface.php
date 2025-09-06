<?php

namespace Domain\Seller\Repositories;

use Domain\Seller\Entities\SellerProfile;
use Domain\Seller\ValueObjects\SellerId; // <- esto es importante

interface SellerProfileRepositoryInterface
{
    public function save(SellerProfile $profile): void;

    public function findById(SellerId $sellerId): ?SellerProfile;
}
