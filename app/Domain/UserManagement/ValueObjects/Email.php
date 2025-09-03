<?php

namespace Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address.");
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
