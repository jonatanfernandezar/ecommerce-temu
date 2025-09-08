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

    /**
     * Approve the seller (domain logic).
     *
     * @throws \DomainException if seller is already approved or not in a pending state
     */
    public function approve(): void
    {
        if ($this->status->isApproved()) {
            throw new \DomainException("Seller {$this->id->value()} is already approved.");
        }

        if (!$this->status->isPending()) {
            throw new \DomainException("Only pending sellers can be approved.");
        }

        $this->status = SellerStatus::approved();
    }

    /**
     * Reject the seller (domain logic).
     *
     * @throws \DomainException if seller already has a final state
     */
    public function reject(): void
    {
        if (!$this->status->isPending()) {
            throw new \DomainException("Only pending sellers can be rejected.");
        }

        $this->status = SellerStatus::rejected();
    }
}
