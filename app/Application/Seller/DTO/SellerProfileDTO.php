<?php

namespace Application\Seller\DTO;

final class SellerProfileDTO
{
    public function __construct(
        public readonly string $sellerId,
        public readonly string $address,
        public readonly string $phone,
        public readonly string $description
    ) {}

    public static function fromDomain(\Domain\Seller\Entities\SellerProfile $profile): self
    {
        return new self(
            $profile->sellerId()->value(),
            $profile->address(),
            $profile->phone(),
            $profile->description()
        );
    }
}