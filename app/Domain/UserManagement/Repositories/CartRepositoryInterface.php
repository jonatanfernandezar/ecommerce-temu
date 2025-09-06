<?php

namespace Domain\UserManagement\Repositories;

use Domain\UserManagement\Entities\ShoppingCart;
use Domain\UserManagement\ValueObjects\CartId;
use Domain\UserManagement\ValueObjects\UserId;

interface CartRepositoryInterface
{
    public function save(ShoppingCart $cart): void;

    public function findById(CartId $id): ?ShoppingCart;

    public function findByUser(UserId $userId): ?ShoppingCart;

    public function delete(ShoppingCart $cart): void;
}
