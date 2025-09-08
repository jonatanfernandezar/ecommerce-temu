<?php

namespace Domain\Ordering\Entities;

use DateTimeImmutable;
use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\OrderItemId;
use Domain\Ordering\ValueObjects\OrderStatus;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\Shared\ValueObjects\Address;
use Domain\Shared\ValueObjects\Money;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Ordering\Events\OrderPlaced;
use Domain\Ordering\Events\OrderCancelled;
use Domain\Ordering\Events\OrderPaid;
use Domain\Ordering\Events\OrderShipped;
use Domain\Ordering\Events\OrderDelivered;
use Domain\Ordering\Exceptions\InvalidOrderStatusException;

final class Order
{
    private OrderId $id;
    private UserId $buyerId;
    /** @var OrderItem[] */
    private array $items = [];
    private Address $shippingAddress;
    private OrderStatus $status;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $paidAt = null;
    private ?DateTimeImmutable $shippedAt = null;
    private ?DateTimeImmutable $deliveredAt = null;
    private string $currency;
    /**
     * @param OrderStatus[] $allowed
     */

    public function __construct(
        OrderId $id,
        UserId $buyerId,
        Address $shippingAddress,
        string $currency = 'USD'
    ) {
        $this->id = $id;
        $this->buyerId = $buyerId;
        $this->shippingAddress = $shippingAddress;
        $this->status = OrderStatus::pending();
        $this->createdAt = new DateTimeImmutable();
        $this->currency = $currency;
    }

    public static function place(UserId $buyerId, Address $shippingAddress, string $currency = 'USD'): self
    {
        $order = new self(OrderId::generate(), $buyerId, $shippingAddress, $currency);
        // event(new OrderPlaced($order)); // habilita cuando integres eventos
        return $order;
    }

    public function id(): OrderId
    {
        return $this->id;
    }
    public function buyerId(): UserId
    {
        return $this->buyerId;
    }
    public function status(): OrderStatus
    {
        return $this->status;
    }
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function paidAt(): ?DateTimeImmutable
    {
        return $this->paidAt;
    }
    public function shippedAt(): ?DateTimeImmutable
    {
        return $this->shippedAt;
    }
    public function deliveredAt(): ?DateTimeImmutable
    {
        return $this->deliveredAt;
    }
    public function shippingAddress(): Address
    {
        return $this->shippingAddress;
    }
    /** @return OrderItem[] */ public function items(): array
    {
        return $this->items;
    }

    public function addItem(ProductId $productId, Quantity $quantity, Money $unitPrice): OrderItem
    {
        $this->assertStatus([OrderStatus::PENDING]);
        $item = new OrderItem(OrderItemId::generate(), $productId, $quantity, $unitPrice);
        $this->items[] = $item;
        return $item;
    }

    public function removeItem(OrderItemId $itemId): void
    {
        $this->assertStatus([OrderStatus::PENDING]);
        $this->items = array_values(array_filter(
            $this->items,
            fn(OrderItem $i) => $i->id()->value() !== $itemId->value()
        ));
    }

    public function total(): Money
    {
        $total = Money::zero($this->currency);
        foreach ($this->items as $item) {
            $total = $total->add($item->lineTotal());
        }
        return $total;
    }

    public function pay(): void
    {
        $this->assertStatus([OrderStatus::PENDING]);
        $this->status = OrderStatus::paid();
        $this->paidAt = new DateTimeImmutable();
        // event(new OrderPaid($this));
    }

    public function ship(): void
    {
        $this->assertStatus([OrderStatus::PAID]);
        $this->status = OrderStatus::shipped();
        $this->shippedAt = new DateTimeImmutable();
        // event(new OrderShipped($this));
    }

    public function deliver(): void
    {
        $this->assertStatus([OrderStatus::SHIPPED]);
        $this->status = OrderStatus::delivered();
        $this->deliveredAt = new DateTimeImmutable();
        // event(new OrderDelivered($this));
    }

    public function cancel(): void
    {
        $this->assertStatus([OrderStatus::PENDING, OrderStatus::PAID]);
        $this->status = OrderStatus::cancelled();
        // event(new OrderCancelled($this));
    }

    public function currency(): string
    {
        return $this->currency;
    }

    private function assertStatus(array $allowed): void
    {
        if (!in_array($this->status->value(), $allowed, true)) {
            throw new InvalidOrderStatusException(
                sprintf(
                    'Current status %s not in allowed transitions: %s',
                    $this->status->value(),
                    implode(',', $allowed)
                )
            );
        }
    }
}
