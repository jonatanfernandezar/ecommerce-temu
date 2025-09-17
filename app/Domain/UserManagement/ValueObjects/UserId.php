<?php

namespace Domain\UserManagement\ValueObjects;

use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class UserId
{
    private string $value;

    public function __construct(string $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("Invalid ID for UserId.");
        }
        $this->value = $value;
    }

     /**
     * En autoincrement, no se genera manualmente.
     */
    public static function generate(): self
    {
        throw new \LogicException("UserId is auto-incremented by the database.");
    }

    public function value(): string
    {
        return $this->value;
    }
}
