<?php

namespace Application\UserManagement\DTO;

use Domain\UserManagement\Entities\ShoppingCart as DomainCart;
use Domain\UserManagement\Entities\CartItem as DomainCartItem;

final class CartDTO
{
    /** @var CartItemDTO[] */
    public array $items;
    public int $totalQuantity;
    public float $totalAmount; // unidades principales

    /**
     * @param CartItemDTO[] $items
    */

    public function __construct(array $items, int $totalQuantity, float $totalAmount)
    {
        $this->items = $items;
        $this->totalQuantity = $totalQuantity;
        $this->totalAmount = $totalAmount;
    }

    public static function fromDomain(DomainCart $cart): self
    {
        $itemsDto = array_map(
            fn (DomainCartItem $i) => CartItemDTO::fromDomain($i),
            $cart->items()
        );

        $totalQty = 0;
        foreach ($cart->items() as $i) {
            $totalQty += $i->quantity()->value();
        }

        $totalMoney = $cart->total(); // Domain\Shared\ValueObjects\Money
        $amount = $totalMoney->amount() / 100;

        return new self($itemsDto, $totalQty, $amount);
    }
}
