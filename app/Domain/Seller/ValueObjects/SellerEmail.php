<?php

namespace Domain\Seller\ValueObjects;

class SellerEmail
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email inválido para el vendedor.");
        }
        $this->value = strtolower($value);
    }

    public function value(): string { return $this->value; }
}
