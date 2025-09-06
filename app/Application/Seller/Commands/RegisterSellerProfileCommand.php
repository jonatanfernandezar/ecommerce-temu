<?php

namespace Application\Seller\Commands;

final class RegisterSellerProfileCommand
{
    public function __construct(
        public string $sellerId,
        public string $address,
        public string $phone,
        public string $description
    ) {}
}