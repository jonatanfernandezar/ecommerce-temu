<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\UpdateCartCommand;
use Application\UserManagement\DTO\CartDTO;
use Domain\UserManagement\Repositories\CartRepositoryInterface;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Shared\ValueObjects\Money;
use Domain\UserManagement\Exceptions\CartDomainException;

final class UpdateCartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(UpdateCartCommand $command): CartDTO
    {
        $userId = new UserId($command->userId);
        $cart = $this->cartRepository->findByUser($userId);

        if (!$cart) {
            throw new CartDomainException("Cart not found for user {$command->userId}");
        }

        $productId = new ProductId($command->productId);

        switch ($command->action) {
            case 'add':
                $product = $this->productRepository->findById($productId->value());
                if (!$product) {
                    throw new CartDomainException("Product not found: {$command->productId}");
                }

                // ✅ Adaptamos Price -> Money
                $priceVO = $product->getPrice();
                $unitPrice = new Money($priceVO->amount(), $priceVO->currency());

                $quantity = new Quantity($command->quantity ?? 1);
                $cart->addItem($productId, $quantity, $unitPrice);
                break;

            case 'update':
                if ($command->quantity === null) {
                    throw new CartDomainException("Quantity required for update");
                }
                $cart->updateQuantity($productId, new Quantity($command->quantity));
                break;

            case 'remove':
                $cart->removeItem($productId);
                break;

            default:
                throw new CartDomainException("Invalid action: {$command->action}");
        }

        $this->cartRepository->save($cart);

        return CartDTO::fromDomain($cart);
    }
}
