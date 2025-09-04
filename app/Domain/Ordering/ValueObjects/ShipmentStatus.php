<?php

namespace Domain\Ordering\ValueObjects;

class ShipmentStatus
{
    private const PENDING = 'pending';
    private const SHIPPED = 'shipped';
    private const DELIVERED = 'delivered';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function shipped(): self
    {
        return new self(self::SHIPPED);
    }

    public static function delivered(): self
    {
        return new self(self::DELIVERED);
    }

    public function value(): string
    {
        return $this->value;
    }
}
