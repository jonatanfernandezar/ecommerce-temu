<?php

namespace Domain\UserManagement\ValueObjects;

use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class CartId
{
    private string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? Uuid::uuid4()->toString();
        if (!Uuid::isValid($this->value)) {
            throw new InvalidArgumentException("Invalid UUID for CartId.");
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new self();
    }
}
