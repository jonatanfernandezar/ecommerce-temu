<?php

namespace Domain\Seller\ValueObjects;

final class SellerStatus
{
    private string $value;

    private const PENDING  = 'pending';
    private const APPROVED = 'approved';
    private const REJECTED = 'rejected';
    private const ACTIVE   = 'active';
    private const INACTIVE = 'inactive';

    private function __construct(string $value)
    {
        $allowed = [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
            self::ACTIVE,
            self::INACTIVE,
        ];

        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid seller status: {$value}");
        }

        $this->value = $value;
    }

    // --- Factories ---
    public static function pending(): self  { return new self(self::PENDING); }
    public static function approved(): self { return new self(self::APPROVED); }
    public static function rejected(): self { return new self(self::REJECTED); }
    public static function active(): self   { return new self(self::ACTIVE); }
    public static function inactive(): self { return new self(self::INACTIVE); }

    // --- State checks ---
    public function isPending(): bool  { return $this->value === self::PENDING; }
    public function isApproved(): bool { return $this->value === self::APPROVED; }
    public function isRejected(): bool { return $this->value === self::REJECTED; }
    public function isActive(): bool   { return $this->value === self::ACTIVE; }
    public function isInactive(): bool { return $this->value === self::INACTIVE; }

    // --- Raw value ---
    public function value(): string { return $this->value; }

    // --- Equality ---
    public function equals(SellerStatus $other): bool
    {
        return $this->value === $other->value;
    }
}
