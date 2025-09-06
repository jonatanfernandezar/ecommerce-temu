<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\DTO\UserProfileDTO;
use Domain\UserManagement\Repositories\UserProfileRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Exceptions\UserDomainException;

final class ViewUserProfileService
{
    public function __construct(
        private UserProfileRepositoryInterface $profileRepository
    ) {}

    public function execute(string $userId): UserProfileDTO
    {
        $profile = $this->profileRepository->findByUserId(new UserId($userId));

        if (!$profile) {
            throw new UserDomainException("Profile not found for user {$userId}");
        }

        return UserProfileDTO::fromDomain($profile);
    }
}
