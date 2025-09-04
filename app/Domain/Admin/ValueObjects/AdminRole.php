<?php

namespace Domain\Admin\ValueObjects;

use Domain\Shared\Exceptions\DomainException;

class AdminRole
{
    public const ADMIN = 'admin';
    public const SUPER_ADMIN = 'super_admin';

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, [self::ADMIN, self::SUPER_ADMIN], true)) {
            throw new DomainException("Invalid admin role: {$value}");
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->value === self::SUPER_ADMIN;
    }
}
