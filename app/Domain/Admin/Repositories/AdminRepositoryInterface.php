<?php

namespace Domain\Admin\Repositories;

use Domain\Admin\Entities\Admin;
use Domain\Admin\ValueObjects\AdminId;

interface AdminRepositoryInterface
{
    public function save(Admin $admin): void;
    public function findById(AdminId $id): ?Admin;
    public function delete(AdminId $id): void;
}
