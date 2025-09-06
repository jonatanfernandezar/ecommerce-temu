<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\CreateUserProfileCommand;
use Application\UserManagement\DTO\UserProfileDTO;
use Domain\UserManagement\Entities\UserProfile;
use Domain\UserManagement\Repositories\UserProfileRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;

final class CreateUserProfileService
{
    public function __construct(
        private UserProfileRepositoryInterface $profileRepository
    ) {}

    public function execute(CreateUserProfileCommand $command): UserProfileDTO
    {
        $profile = new UserProfile(
            new UserId($command->userId),
            $command->name,
            $command->email,
            $command->address,
            $command->phone
        );

        $this->profileRepository->save($profile);

        return UserProfileDTO::fromDomain($profile);
    }
}
