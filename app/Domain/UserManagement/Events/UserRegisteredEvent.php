<?php

namespace Domain\UserManagement\Events;

use Domain\UserManagement\Entities\User;

final class UserRegisteredEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user(): User
    {
        return $this->user;
    }
}
