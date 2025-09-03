<?php

namespace Domain\UserManagement\Repositories;

use Domain\UserManagement\Entities\User;
use Domain\UserManagement\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(UserId $id): ?User;

    public function findByEmail(string $email): ?User;

    public function delete(User $user): void;
}
