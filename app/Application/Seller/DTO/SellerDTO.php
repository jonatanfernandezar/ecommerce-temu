<?php

namespace Application\Seller\DTO;

use Domain\Seller\Entities\Seller;

final class SellerDTO
{
    public function __construct(
        public readonly string $sellerId,
        public readonly string $name,
        public readonly string $email,
        public readonly string $status
    ) {}

    public static function fromDomain(Seller $seller): self
    {
        return new self(
            $seller->id()->value(),
            $seller->name()->value(),
            $seller->email()->value(),
            $seller->status()->value()
        );
    }
}
