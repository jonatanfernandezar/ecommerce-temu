<?php

namespace Application\UserManagement\Commands;

final class UpdateUserProfileCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $address = null,
        public readonly ?string $phone = null
    ) {}
}
