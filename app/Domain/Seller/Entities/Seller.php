<?php

namespace Domain\Seller\Entities;

use Domain\Seller\ValueObjects\SellerId;
use Domain\Seller\ValueObjects\SellerName;
use Domain\Seller\ValueObjects\SellerEmail;
use Domain\Seller\ValueObjects\SellerStatus;

class Seller
{
    private SellerId $id;
    private SellerName $name;
    private SellerEmail $email;
    private SellerStatus $status;

    public function __construct(
        SellerId $id,
        SellerName $name,
        SellerEmail $email,
        SellerStatus $status
    ) {
        $this->id     = $id;
        $this->name   = $name;
        $this->email  = $email;
        $this->status = $status;
    }

    public function id(): SellerId { return $this->id; }
    public function name(): SellerName { return $this->name; }
    public function email(): SellerEmail { return $this->email; }
    public function status(): SellerStatus { return $this->status; }

    public function deactivate(): void
    {
        $this->status = SellerStatus::inactive();
    }

    public function activate(): void
    {
        $this->status = SellerStatus::active();
    }
}
