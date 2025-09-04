<?php

namespace Domain\Shared\ValueObjects;

use InvalidArgumentException;

final class Address
{
    private string $line1;
    private ?string $line2;
    private string $city;
    private string $state;
    private string $postalCode;
    private string $country;

    public function __construct(
        string $line1,
        ?string $line2,
        string $city,
        string $state,
        string $postalCode,
        string $country = 'US'
    ) {
        $line1 = trim($line1);
        $city = trim($city);
        $state = trim($state);
        $postalCode = trim($postalCode);
        $country = strtoupper(trim($country));

        if ($line1 === '' || $city === '' || $state === '' || $postalCode === '' || strlen($country) !== 2) {
            throw new InvalidArgumentException('Invalid address fields.');
        }
        $this->line1 = $line1;
        $this->line2 = $line2 ? trim($line2) : null;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    public function line1(): string { return $this->line1; }
    public function line2(): ?string { return $this->line2; }
    public function city(): string { return $this->city; }
    public function state(): string { return $this->state; }
    public function postalCode(): string { return $this->postalCode; }
    public function country(): string { return $this->country; }
}
