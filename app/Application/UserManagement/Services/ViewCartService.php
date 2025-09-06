<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\DTO\CartDTO;
use Domain\UserManagement\Repositories\CartRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Exceptions\CartDomainException;

final class ViewCartService
{
    public function __construct(private CartRepositoryInterface $cartRepository) {}

    public function execute(string $userIdString): CartDTO
    {
        $userId = new UserId($userIdString);
        $cart = $this->cartRepository->findByUser($userId);

        if (!$cart) {
            throw new CartDomainException("Cart not found for user {$userIdString}");
        }

        return CartDTO::fromDomain($cart);
    }
}
