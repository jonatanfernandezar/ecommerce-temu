<?php

namespace Application\UserManagement\DTO;

use Domain\UserManagement\Entities\User;

final class UserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $role
    ) {}

    public static function fromDomain(User $user): self
    {
        return new self(
            (string) $user->id()->value(), // Asegurarse de convertir a string si es necesario - // puede ser null si aún no persistió
            $user->name()->value(),
            $user->email()->value(),
            $user->role()->value()
        );
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];
    }
}
