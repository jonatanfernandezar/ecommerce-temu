<?php

namespace App\Domain\Catalog\ValueObjects;

class Price
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'USD')
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Price cannot be negative.");
        }

        $this->amount   = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return number_format($this->amount, 2) . " " . $this->currency;
    }
}
