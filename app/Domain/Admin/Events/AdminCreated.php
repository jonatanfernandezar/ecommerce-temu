<?php

namespace Domain\Admin\Events;

use Domain\Admin\ValueObjects\AdminId;

class AdminCreated
{
    public function __construct(public readonly AdminId $adminId) {}
}
