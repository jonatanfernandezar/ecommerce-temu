<?php

namespace App\Domain\Catalog\Entities;

use App\Domain\Catalog\ValueObjects\Price;
use App\Domain\Catalog\Events\ProductCreatedEvent;

class Product
{
    private string $id;
    private string $name;
    private string $description;
    private Price $price;
    private int $stock;
    private string $categoryId;

    public function __construct(
        string $id,
        string $name,
        string $description,
        Price $price,
        int $stock,
        string $categoryId
    ) {
        if (empty($id) || empty($name) || empty($categoryId)) {
            throw new \InvalidArgumentException("ID, name and categoryId are required.");
        }

        $this->id          = $id;
        $this->name        = $name;
        $this->description = $description;
        $this->price       = $price;
        $this->stock       = $stock;
        $this->categoryId  = $categoryId;

        // Aquí podrías despachar un evento de dominio si usas un event bus
        // new ProductCreatedEvent($this->id, $this->name);
    }

    // Métodos de negocio
    public function changePrice(Price $newPrice): void
    {
        $this->price = $newPrice;
    }

    public function decreaseStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than 0.");
        }

        if ($this->stock < $quantity) {
            throw new \RuntimeException("Not enough stock available.");
        }

        $this->stock -= $quantity;
    }

    public function increaseStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than 0.");
        }

        $this->stock += $quantity;
    }

    // Getters
    public function getId(): string     { return $this->id; }
    public function getName(): string   { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getPrice(): Price   { return $this->price; }
    public function getStock(): int     { return $this->stock; }
    public function getCategoryId(): string { return $this->categoryId; }
}
