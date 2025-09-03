<?php

namespace Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class UserRole
{
    private const ROLES = ['client', 'seller', 'admin'];
    private string $role;

    public function __construct(string $role)
    {
        if (!in_array($role, self::ROLES)) {
            throw new InvalidArgumentException("Invalid user role.");
        }
        $this->role = $role;
    }

    public function value(): string
    {
        return $this->role;
    }
}
