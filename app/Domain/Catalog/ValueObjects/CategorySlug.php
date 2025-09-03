<?php

namespace Domain\Catalog\ValueObjects;

use InvalidArgumentException;

final class CategorySlug
{
    private string $value;

    public function __construct(string $value)
    {
        $value = strtolower(trim($value));

        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            throw new InvalidArgumentException("Invalid slug format for CategorySlug.");
        }

        $this->value = $value;
    }

    public static function fromName(CategoryName $name): self
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name->value())));
        return new self($slug);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
