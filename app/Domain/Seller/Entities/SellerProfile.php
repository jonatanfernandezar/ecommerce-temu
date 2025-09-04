<?php

namespace Domain\Seller\Entities;

use Domain\Seller\ValueObjects\SellerId;

class SellerProfile
{
    private SellerId $sellerId;
    private string $address;
    private string $phone;
    private string $description;

    public function __construct(SellerId $sellerId, string $address, string $phone, string $description)
    {
        $this->sellerId    = $sellerId;
        $this->address     = $address;
        $this->phone       = $phone;
        $this->description = $description;
    }

    public function sellerId(): SellerId { return $this->sellerId; }
    public function address(): string { return $this->address; }
    public function phone(): string { return $this->phone; }
    public function description(): string { return $this->description; }

    public function update(string $address, string $phone, string $description): void
    {
        $this->address     = $address;
        $this->phone       = $phone;
        $this->description = $description;
    }
}
