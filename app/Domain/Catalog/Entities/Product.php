<?php

namespace App\Domain\Catalog\Entities;

use App\Domain\Catalog\ValueObjects\Price;
use App\Domain\Catalog\Events\ProductCreatedEvent;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\BrandId;
use Domain\Shared\ValueObjects\Quantity;

class Product
{
    private ProductId $id;
    private string $name;
    private string $description;
    private Price $price;
    private Quantity $stock;
    private CategoryId $categoryId;
    private ?BrandId $brandId;
    /** @var array<string, mixed> */
    private array $attributes;
    /**
     * @param array<string, mixed> $attributes
     */

    public function __construct(
        ProductId $id,
        string $name,
        string $description,
        Price $price,
        Quantity $stock,
        CategoryId $categoryId,
        ?BrandId $brandId = null,
        array $attributes = []
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
        $this->brandId = $brandId;
        $this->attributes = $attributes;

        // Aquí podrías despachar un evento de dominio si usas un event bus
        // new ProductCreatedEvent($this->id, $this->name);
    }

    // Métodos de negocio
    public function changePrice(Price $newPrice): void
    {
        $this->price = $newPrice;
    }

    public function decreaseStock(Quantity $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than 0.");
        }

        if ($this->stock < $quantity) {
            throw new \RuntimeException("Not enough stock available.");
        }

        $this->stock = $this->stock->subtract($quantity);
    }

    public function increaseStock(Quantity $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than 0.");
        }

        $this->stock = $this->stock->add($quantity);
    }

    // Getters
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getPrice(): Price
    {
        return $this->price;
    }
    public function getStock(): int
    {
        return $this->stock->value();
    }
    /** @return array<string, mixed> */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function rename(string $newName): void
    {
        if (empty($newName)) {
            throw new \InvalidArgumentException("Name cannot be empty.");
        }
        $this->name = $newName;
    }

    public function changeDescription(string $newDescription): void
    {
        $this->description = $newDescription;
    }

    public function resetStock(int $newStock): void
    {
        if ($newStock < 0) {
            throw new \InvalidArgumentException("Stock cannot be negative.");
        }
        $this->stock = new Quantity($newStock);
    }

    public function getId(): ProductId
    {
        return $this->id;
    }
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
    public function getBrandId(): ?BrandId
    {
        return $this->brandId;
    }
}
