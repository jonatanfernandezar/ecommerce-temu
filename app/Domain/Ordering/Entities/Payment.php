<?php

namespace Domain\Ordering\Entities;

use Domain\Shared\ValueObjects\Money;
use Domain\Ordering\ValueObjects\PaymentId;
use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\PaymentStatus;

class Payment
{
    private PaymentId $id;
    private OrderId $orderId;
    private Money $amount;
    private PaymentStatus $status;

    public function __construct(
        PaymentId $id,
        OrderId $orderId,
        Money $amount,
        PaymentStatus $status
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->status = $status;
    }

    public function id(): PaymentId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function complete(): void
    {
        $this->status = PaymentStatus::completed();
    }

    public function fail(): void
    {
        $this->status = PaymentStatus::failed();
    }
}
