<?php

namespace Application\UserManagement\DTO;

use Domain\UserManagement\Entities\User;

final class UserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $role
    ) {}

    public static function fromDomain(User $user): self
    {
        return new self(
            (string) $user->id()->value(),
            $user->email()->value(),
            $user->role()->value()
        );
    }
}
