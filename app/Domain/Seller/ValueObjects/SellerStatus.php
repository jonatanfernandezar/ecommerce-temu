<?php

namespace Domain\Seller\ValueObjects;

class SellerStatus
{
    private string $value;

    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function active(): self { return new self(self::ACTIVE); }
    public static function inactive(): self { return new self(self::INACTIVE); }

    public function value(): string { return $this->value; }
}
