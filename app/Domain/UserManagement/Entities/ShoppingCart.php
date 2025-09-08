<?php

namespace Domain\UserManagement\Entities;

use Domain\UserManagement\ValueObjects\CartId;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Shared\ValueObjects\Money;

final class ShoppingCart
{
    private CartId $id;
    private UserId $userId;
    /** @var CartItem[] */
    private array $items;
    private \DateTimeImmutable $updatedAt;
    /**
     * @param CartItem[] $items
    */
    
    public function __construct(CartId $id, UserId $userId, array $items = [])
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->items = $items;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id(): CartId { return $this->id; }
    public function userId(): UserId { return $this->userId; }
    /** @return CartItem[] */
    public function items(): array { return $this->items; }
    public function updatedAt(): \DateTimeImmutable { return $this->updatedAt; }

    public function addItem(ProductId $productId, Quantity $quantity, Money $unitPrice): void
    {
        // buscar si ya existe
        foreach ($this->items as $item) {
            if ($item->productId()->value() === $productId->value()) {
                $item->increaseQuantity($quantity);
                $this->touch();
                return;
            }
        }

        $this->items[] = new CartItem($productId, $quantity, $unitPrice);
        $this->touch();
    }

    public function removeItem(ProductId $productId): void
    {
        $this->items = array_values(array_filter(
            $this->items,
            fn (CartItem $i) => $i->productId()->value() !== $productId->value()
        ));
        $this->touch();
    }

    public function updateQuantity(ProductId $productId, Quantity $newQuantity): void
    {
        foreach ($this->items as $index => $item) {
            if ($item->productId()->value() === $productId->value()) {
                if ($newQuantity->value() <= 0) {
                    $this->removeItem($productId);
                    return;
                }
                $item->changeQuantity($newQuantity);
                $this->touch();
                return;
            }
        }
    }

    public function total(): Money
    {
        $currency = $this->items[0]->unitPrice()->currency() ?? 'USD';
        $total = \Domain\Shared\ValueObjects\Money::zero($currency);
        foreach ($this->items as $item) {
            $total = $total->add($item->lineTotal());
        }
        return $total;
    }

    public function clear(): void
    {
        $this->items = [];
        $this->touch();
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
