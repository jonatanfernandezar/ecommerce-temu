<?php

namespace Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class PaymentMethod
{
    private const ALLOWED = ['card', 'paypal', 'simulated'];

    private string $method;

    public function __construct(string $method)
    {
        $method = strtolower(trim($method));
        if (!in_array($method, self::ALLOWED, true)) {
            throw new InvalidArgumentException("Unsupported payment method: {$method}");
        }
        $this->method = $method;
    }

    public function value(): string
    {
        return $this->method;
    }

    public static function simulated(): self { return new self('simulated'); }
}
