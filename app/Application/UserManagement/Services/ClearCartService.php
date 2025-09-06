<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\ClearCartCommand;
use Domain\UserManagement\Repositories\CartRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Exceptions\CartDomainException;

final class ClearCartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(ClearCartCommand $command): void
    {
        $userId = new UserId($command->userId);
        $cart = $this->cartRepository->findByUser($userId);

        if (!$cart) {
            throw new CartDomainException("Cart not found for user {$command->userId}");
        }

        $cart->clear();
        $this->cartRepository->save($cart);
    }
}
