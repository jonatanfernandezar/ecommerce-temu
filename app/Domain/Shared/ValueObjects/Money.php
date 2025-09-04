<?php

namespace Domain\Shared\ValueObjects;

use InvalidArgumentException;

final class Money
{
    private int $amount; // en centavos
    private string $currency;

    public function __construct(int $amount, string $currency = 'USD')
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Money amount cannot be negative.');
        }
        if (strlen($currency) !== 3) {
            throw new InvalidArgumentException('Currency must be a 3-letter ISO code.');
        }
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    public static function zero(string $currency = 'USD'): self
    {
        return new self(0, $currency);
    }

    public function amount(): int { return $this->amount; }
    public function currency(): string { return $this->currency; }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount(), $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        if ($other->amount() > $this->amount) {
            throw new InvalidArgumentException('Cannot subtract more than current amount.');
        }
        return new self($this->amount - $other->amount(), $this->currency);
    }

    public function multiply(int $factor): self
    {
        if ($factor < 0) {
            throw new InvalidArgumentException('Factor cannot be negative.');
        }
        return new self($this->amount * $factor, $this->currency);
    }

    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency()) {
            throw new InvalidArgumentException('Currency mismatch.');
        }
    }
}
