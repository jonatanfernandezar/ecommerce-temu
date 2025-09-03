<?php

namespace App\Domain\Catalog\Events;

class ProductCreatedEvent
{
    private string $productId;
    private string $name;

    public function __construct(string $productId, string $name)
    {
        $this->productId = $productId;
        $this->name      = $name;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
