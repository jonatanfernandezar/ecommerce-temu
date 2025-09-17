<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\LoginUserCommand;
use Application\UserManagement\DTO\UserDTO;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\Exceptions\UserDomainException;
use Domain\UserManagement\ValueObjects\Email;

final class LoginUserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(LoginUserCommand $command): UserDTO
    {
        // Crear VO Email a partir del string del comando
        $email = new Email($command->email);
        // Buscar usuario por email
        $user = $this->userRepository->findByEmail($email);

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
