<?php

namespace Domain\UserManagement\Events;

use Domain\UserManagement\ValueObjects\UserId;
use DateTimeImmutable;

final class UserLoggedInEvent
{
    private UserId $userId;
    private DateTimeImmutable $when;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->when = new DateTimeImmutable();
    }

    public function userId(): UserId { return $this->userId; }
    public function when(): DateTimeImmutable { return $this->when; }
}
