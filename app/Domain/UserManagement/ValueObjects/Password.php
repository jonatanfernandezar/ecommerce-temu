<?php

namespace Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class Password
{
    private string $hash;

    // Nuevo constructor privado para internal use
    private function __construct(string $hash, bool $isHashed = false)
    {
        if ($isHashed) {
            $this->hash = $hash;
        } else {
            if (strlen($hash) < 8) {
                throw new \InvalidArgumentException("Password must be at least 8 characters.");
            }
            $this->hash = password_hash($hash, PASSWORD_DEFAULT);
        }
    }

    // Constructor público desde texto plano
    public static function fromPlain(string $plainPassword): self
    {
        return new self($plainPassword);
    }

    // Constructor desde hash
    public static function fromHash(string $hash): self
    {
        return new self($hash, true);
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
