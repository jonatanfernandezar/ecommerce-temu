<?php

namespace Domain\Catalog\ValueObjects;

final class ProductStatus
{
    private string $value;

    private const ACTIVE  = 'active';
    private const REMOVED = 'removed';

    private function __construct(string $value)
    {
        $allowed = [self::ACTIVE, self::REMOVED];
        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid product status: {$value}");
        }
        $this->value = $value;
    }

    public static function active(): self { return new self(self::ACTIVE); }
    public static function removed(): self { return new self(self::REMOVED); }

    public function isActive(): bool { return $this->value === self::ACTIVE; }
    public function isRemoved(): bool { return $this->value === self::REMOVED; }

    public function value(): string { return $this->value; }

    public function equals(ProductStatus $other): bool
    {
        return $this->value === $other->value;
    }
}
