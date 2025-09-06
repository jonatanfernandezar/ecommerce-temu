<?php

namespace Application\UserManagement\DTO;

use Domain\UserManagement\Entities\UserProfile;

class UserProfileDTO
{
    public function __construct(
        public string $userId,
        public string $name,
        public string $email,
        public ?string $address = null,
        public ?string $phone = null
    ) {}

    public static function fromDomain(UserProfile $profile): self
    {
        return new self(
            $profile->getUserId()->value(),
            $profile->getName(),
            $profile->getEmail(),
            $profile->getAddress(),
            $profile->getPhone()
        );
    }
}
