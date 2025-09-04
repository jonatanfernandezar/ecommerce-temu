<?php

namespace Domain\Ordering\ValueObjects;

use InvalidArgumentException;

final class OrderStatus
{
    public const PENDING   = 'pending';
    public const PAID      = 'paid';
    public const SHIPPED   = 'shipped';
    public const DELIVERED = 'delivered';
    public const CANCELLED = 'cancelled';

    private string $value;

    public function __construct(string $value)
    {
        $allowed = [self::PENDING, self::PAID, self::SHIPPED, self::DELIVERED, self::CANCELLED];
        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException('Invalid order status.');
        }
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public static function pending(): self { return new self(self::PENDING); }
    public static function paid(): self { return new self(self::PAID); }
    public static function shipped(): self { return new self(self::SHIPPED); }
    public static function delivered(): self { return new self(self::DELIVERED); }
    public static function cancelled(): self { return new self(self::CANCELLED); }
}
