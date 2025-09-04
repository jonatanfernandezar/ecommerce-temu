<?php

namespace Domain\Seller\ValueObjects;

class SellerName
{
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 3) {
            throw new \InvalidArgumentException("El nombre del vendedor debe tener al menos 3 caracteres.");
        }
        $this->value = $value;
    }

    public function value(): string { return $this->value; }
}
