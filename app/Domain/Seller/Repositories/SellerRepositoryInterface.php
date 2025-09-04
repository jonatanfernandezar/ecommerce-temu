<?php

namespace Domain\Seller\Repositories;

use Domain\Seller\Entities\Seller;
use Domain\Seller\ValueObjects\SellerId;

interface SellerRepositoryInterface
{
    public function save(Seller $seller): void;
    public function findById(SellerId $id): ?Seller;
    public function delete(SellerId $id): void;
}
