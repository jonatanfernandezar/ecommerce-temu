<?php

namespace Domain\Catalog\ValueObjects;

use InvalidArgumentException;

final class AttributeName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException("Attribute name cannot be empty.");
        }
        if (strlen($value) > 100) {
            throw new InvalidArgumentException("Attribute name too long.");
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
