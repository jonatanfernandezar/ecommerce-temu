<?php

namespace Application\UserManagement\Commands;

final class CreateUserProfileCommand
{
    public function __construct(
        public string $userId,
        public string $name,
        public string $email,
        public ?string $address = null,
        public ?string $phone = null
    ) {}
}
