<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\RegisterUserCommand;
use Application\UserManagement\DTO\UserDTO;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\Entities\User;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\ValueObjects\Password;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\UserRole;

final class RegisterUserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(RegisterUserCommand $command): UserDTO
    {
        // Crear entidad de dominio
        $user = new User(
            UserId::generate(),
            new Email($command->email),
            new Password($command->password),
            new UserRole($command->role)
        );

        // Guardar en repositorio (Infraestructura implementará la persistencia)
        $this->userRepository->save($user);

        // Retornar DTO
        return UserDTO::fromDomain($user);
    }
}
