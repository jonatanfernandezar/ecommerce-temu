<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\UpdateUserProfileCommand;
use Application\UserManagement\DTO\UserProfileDTO;
use Domain\UserManagement\Repositories\UserProfileRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Exceptions\UserDomainException;

final class UpdateUserProfileService
{
    public function __construct(
        private UserProfileRepositoryInterface $profileRepository
    ) {}

    public function execute(UpdateUserProfileCommand $command): UserProfileDTO
    {
        $profile = $this->profileRepository->findByUserId(new UserId($command->userId));

        if (!$profile) {
            throw new UserDomainException("Profile not found for user {$command->userId}");
        }

        $profile->updateProfile(
            $command->name,
            $command->email,
            $command->address,
            $command->phone
        );

        $this->profileRepository->save($profile);

        return UserProfileDTO::fromDomain($profile);
    }
}
