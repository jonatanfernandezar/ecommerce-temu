<?php

namespace Domain\Shared\ValueObjects;

use InvalidArgumentException;

final class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }
        $this->value = $value;
    }

    public function value(): int { return $this->value; }

    public function add(Quantity $other): self
    {
        return new self($this->value + $other->value());
    }

    public function subtract(Quantity $other): self
    {
        if ($other->value() > $this->value) {
            throw new InvalidArgumentException('Cannot subtract more than available quantity.');
        }
        return new self($this->value - $other->value());
    }
}
