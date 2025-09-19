<?php

namespace Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class UserRole
{
    private const ROLES = ['client', 'seller', 'admin'];
    private string $role;

    private function __construct(string $role)
    {
        if (!in_array($role, self::ROLES, true)) {
            throw new \InvalidArgumentException("Invalid user role: {$role}");
        }
        $this->role = $role;
    }

    public static function fromString(string $role): self
    {
        return new self($role);
    }

    public function value(): string
    {
        return $this->role;
    }
}

