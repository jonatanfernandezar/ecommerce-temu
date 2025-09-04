<?php

namespace Domain\Admin\Events;

use Domain\Admin\ValueObjects\AdminId;

class AdminDeactivated
{
    public function __construct(public readonly AdminId $adminId) {}
}
