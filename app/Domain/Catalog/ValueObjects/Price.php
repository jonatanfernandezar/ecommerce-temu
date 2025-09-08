<?php

namespace App\Domain\Catalog\ValueObjects;

final class Price
{
    private int $amount;   // en centavos
    private string $currency;

    public function __construct(int $amount, string $currency = 'USD')
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Amount must be >= 0");
        }
        if (strlen($currency) !== 3) {
            throw new \InvalidArgumentException("Currency must be a 3-letter ISO code");
        }

        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public static function fromFloat(float $amount, string $currency = 'USD'): self
    {
        return new self($amount, $currency);
    }

    // ✅ Helper para convertir directamente a Money
    public function toMoney(): \Domain\Shared\ValueObjects\Money
    {
        return new \Domain\Shared\ValueObjects\Money($this->amount, $this->currency);
    }
}
