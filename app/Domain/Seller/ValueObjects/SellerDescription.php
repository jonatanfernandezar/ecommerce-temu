<?php

namespace Domain\Seller\ValueObjects;

class SellerDescription
{
    private string $text;

    public function __construct(string $text)
    {
        if (strlen($text) < 10) {
            throw new \InvalidArgumentException("Description must be at least 10 characters");
        }
        $this->text = $text;
    }

    public function value(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
