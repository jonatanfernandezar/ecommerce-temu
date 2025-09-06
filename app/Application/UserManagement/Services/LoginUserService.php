<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\LoginUserCommand;
use Application\UserManagement\DTO\UserDTO;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\Exceptions\UserDomainException;

final class LoginUserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(LoginUserCommand $command): UserDTO
    {
        // Buscar usuario por email
        $user = $this->userRepository->findByEmail($command->email);

        if (!$user) {
            throw new UserDomainException("User not found with email {$command->email}");
        }

        // Validar contraseña usando el VO Password
        if (!$user->password()->verify($command->password)) {
            throw new UserDomainException("Invalid credentials");
        }

        // Retornar DTO
        return UserDTO::fromDomain($user);
    }
}
