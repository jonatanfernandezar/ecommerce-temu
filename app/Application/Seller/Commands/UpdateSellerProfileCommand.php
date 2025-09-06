<?php

namespace Application\Seller\Commands;

final class UpdateSellerProfileCommand
{
    public function __construct(
        public string $sellerId,
        public string $address,
        public string $phone,
        public string $description
    ) {}
}