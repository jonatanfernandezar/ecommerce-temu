<?php

namespace Domain\Admin\Entities;

use Domain\Admin\ValueObjects\AdminId;
use Domain\Admin\ValueObjects\AdminRole;

class Admin
{
    private AdminId $id;
    private AdminRole $role;

    public function __construct(AdminId $id, AdminRole $role)
    {
        $this->id = $id;
        $this->role = $role;
    }

    public function id(): AdminId
    {
        return $this->id;
    }

    public function role(): AdminRole
    {
        return $this->role;
    }

    public function canApproveSeller(): bool
    {
        return $this->role->isAdmin() || $this->role->isSuperAdmin();
    }

    public function canBlockUser(): bool
    {
        return $this->role->isAdmin() || $this->role->isSuperAdmin();
    }

    public function canRemoveProduct(): bool
    {
        return $this->role->isSuperAdmin();
    }
}
