<?php

namespace Domain\Ordering\ValueObjects;

use Ramsey\Uuid\Uuid;

class ShipmentId
{
    private string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? Uuid::uuid4()->toString();
    }

    public function value(): string
    {
        return $this->value;
    }
}
