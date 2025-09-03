<?php

namespace Domain\Catalog\ValueObjects;

use InvalidArgumentException;

final class BrandName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('Brand name cannot be empty.');
        }

        if (strlen($value) > 100) {
            throw new InvalidArgumentException('Brand name cannot exceed 100 characters.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
