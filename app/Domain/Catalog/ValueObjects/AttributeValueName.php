<?php

namespace Domain\Catalog\ValueObjects;

use InvalidArgumentException;

final class AttributeValueName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException("AttributeValue name cannot be empty.");
        }
        if (strlen($value) > 100) {
            throw new InvalidArgumentException("AttributeValue name too long.");
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
