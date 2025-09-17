<?php

namespace Domain\UserManagement\ValueObjects;

final class Name
{
    public function __construct(private string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
