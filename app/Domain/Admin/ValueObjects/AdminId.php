<?php

namespace Domain\Admin\ValueObjects;

use Domain\Shared\Exceptions\DomainException;
use Ramsey\Uuid\Uuid;

class AdminId
{
    private string $value;

    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new DomainException("Invalid AdminId");
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

    public function equals(AdminId $other): bool
    {
        return $this->value === $other->value();
    }
}
