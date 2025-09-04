<?php

namespace Domain\Ordering\Entities;

use Domain\Shared\ValueObjects\Address;
use Domain\Ordering\ValueObjects\ShipmentId;
use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\ShipmentStatus;

class Shipment
{
    private ShipmentId $id;
    private OrderId $orderId;
    private Address $address;
    private ShipmentStatus $status;

    public function __construct(
        ShipmentId $id,
        OrderId $orderId,
        Address $address,
        ShipmentStatus $status
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->address = $address;
        $this->status = $status;
    }

    public function id(): ShipmentId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function status(): ShipmentStatus
    {
        return $this->status;
    }

    public function markShipped(): void
    {
        $this->status = ShipmentStatus::shipped();
    }

    public function markDelivered(): void
    {
        $this->status = ShipmentStatus::delivered();
    }
}
