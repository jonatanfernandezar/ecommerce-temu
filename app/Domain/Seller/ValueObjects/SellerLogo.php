<?php

namespace Domain\Seller\ValueObjects;

class SellerLogo
{
    private string $url;

    public function __construct(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Invalid logo URL");
        }
        $this->url = $url;
    }

    public function value(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
