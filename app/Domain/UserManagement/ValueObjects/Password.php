<?php

namespace Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class Password
{
    private string $hash;

    public function __construct(string $plainPassword)
    {
        if (strlen($plainPassword) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters.");
        }
        $this->hash = password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public function hash(): string
    {
        return $this->hash;
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hash);
    }
}
