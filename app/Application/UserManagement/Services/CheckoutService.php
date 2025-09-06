<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\CheckoutCommand;
use Application\UserManagement\DTO\OrderDTO;
use Domain\UserManagement\Repositories\CartRepositoryInterface;
use Domain\Ordering\Repositories\OrderRepository;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Exceptions\CartDomainException;
use Domain\Ordering\Entities\Order;
use Domain\Ordering\Entities\OrderItem;
use Domain\Ordering\ValueObjects\OrderItemId;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Shared\ValueObjects\Money;
use Domain\Shared\ValueObjects\Address; // puedes decidir de dónde tomar la dirección

final class CheckoutService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private OrderRepository $orderRepository
    ) {}

    public function execute(CheckoutCommand $command): OrderDTO
    {
        $userId = new UserId($command->userId);
        $cart = $this->cartRepository->findByUser($userId);

        if (!$cart || $cart->isEmpty()) {
            throw new CartDomainException("Cannot checkout empty cart");
        }

        // Crear la Order directamente desde el método de fábrica
        $order = Order::place(
            $userId,
            $command->shippingAddress, // debería ser un ValueObject Address
            'USD'
        );

        // Pasar items del carrito al pedido
        foreach ($cart->items() as $cartItem) {
            $order->addItem(
                new ProductId($cartItem->productId()->value()),
                new Quantity($cartItem->quantity()->value()),
                new Money($cartItem->unitPrice()->amount(), $cartItem->unitPrice()->currency())
            );
        }

        $this->orderRepository->save($order);

        // Vaciar carrito
        $cart->clear();
        $this->cartRepository->save($cart);

        return OrderDTO::fromDomain($order);
    }
}
