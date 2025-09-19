<?php

namespace Domain\UserManagement\ValueObjects;

final class UserStatus
{
    private string $value;

    private const ACTIVE   = 'active';
    private const BLOCKED  = 'blocked';

    private function __construct(string $value)
    {
        $allowed = [self::ACTIVE, self::BLOCKED];

        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid user status: {$value}");
        }

        $this->value = $value;
    }

    // --- Factories ---
    public static function active(): self { return new self(self::ACTIVE); }
    public static function blocked(): self { return new self(self::BLOCKED); }

    // --- Constructor desde string (DB) ---
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    // --- Checks ---
    public function isActive(): bool { return $this->value === self::ACTIVE; }
    public function isBlocked(): bool { return $this->value === self::BLOCKED; }

    // --- Raw ---
    public function value(): string { return $this->value; }

    public function equals(UserStatus $other): bool
    {
        return $this->value === $other->value;
    }
}

