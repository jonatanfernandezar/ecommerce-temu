<?php

namespace Domain\UserManagement\Events;

use Domain\UserManagement\ValueObjects\CartId;
use Domain\UserManagement\ValueObjects\UserId;

final class CartUpdatedEvent
{
    private CartId $cartId;
    private UserId $userId;

    public function __construct(CartId $cartId, UserId $userId)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
    }

    public function cartId(): CartId { return $this->cartId; }
    public function userId(): UserId { return $this->userId; }
}
