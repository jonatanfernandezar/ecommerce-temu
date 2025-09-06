<?php

namespace Domain\UserManagement\Repositories;

use Domain\UserManagement\Entities\UserProfile;
use Domain\UserManagement\ValueObjects\UserId;

interface UserProfileRepositoryInterface
{
    public function save(UserProfile $profile): void;

    public function findByUserId(UserId $userId): ?UserProfile;

    public function delete(UserProfile $profile): void;
}
