<?php

namespace Domain\Catalog\ValueObjects;

use InvalidArgumentException;

final class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException("Quantity cannot be negative.");
        }
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(int $amount): void
    {
        $this->value += $amount;
    }

    public function subtract(int $amount): void
    {
        if ($amount > $this->value) {
            throw new InvalidArgumentException("Cannot subtract more than available quantity.");
        }
        $this->value -= $amount;
    }
}
