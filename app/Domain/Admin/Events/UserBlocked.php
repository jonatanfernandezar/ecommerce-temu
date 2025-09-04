<?php

namespace Domain\Admin\Events;

use Domain\UserManagement\ValueObjects\UserId;

class UserBlocked
{
    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
