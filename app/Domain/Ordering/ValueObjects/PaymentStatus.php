<?php

namespace Domain\Ordering\ValueObjects;

class PaymentStatus
{
    private const PENDING = 'pending';
    private const COMPLETED = 'completed';
    private const FAILED = 'failed';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    public static function failed(): self
    {
        return new self(self::FAILED);
    }

    public function value(): string
    {
        return $this->value;
    }
}
