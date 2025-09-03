<?php

namespace Domain\Catalog\ValueObjects;

use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class ProductId
{
    private string $value;

    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException("Invalid UUID for ProductId.");
        }
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }
}
