<?php

namespace Domain\Ordering\Repositories;

use Domain\Ordering\Entities\Shipment;
use Domain\Ordering\ValueObjects\ShipmentId;

interface ShipmentRepositoryInterface
{
    public function save(Shipment $shipment): void;
    public function findById(ShipmentId $id): ?Shipment;
}
