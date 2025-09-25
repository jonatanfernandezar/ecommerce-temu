<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\RegisterUserCommand;
use Application\UserManagement\DTO\UserDTO;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\Entities\User;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\ValueObjects\Password;
use Domain\UserManagement\ValueObjects\UserRole;
use Domain\UserManagement\ValueObjects\Name;
use Domain\UserManagement\ValueObjects\UserStatus;
use Illuminate\Support\Facades\Log;
use Domain\UserManagement\Exceptions\UserDomainException;

final class RegisterUserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(RegisterUserCommand $command): UserDTO
    {
        Log::info("▶️ Entrando en RegisterUserService::execute", (array) $command);
        
        // Verificar email único
        if ($this->userRepository->findByEmail(new Email($command->email))) {
            throw new UserDomainException("Email already exists");
        }

        // Crear entidad de dominio
        $user = new User(
            null, // ID será asignado por la base de datos
            new Name($command->name),
            new Email($command->email),
            Password::fromPlain($command->password),
            UserRole::fromString($command->role),
            UserStatus::active()
        );

        Log::info("🛠️ Entidad User creada", (array) $user);

        // Guardar en repositorio (Infraestructura implementará la persistencia)
        $this->userRepository->save($user);
        Log::info("💾 Usuario guardado en repositorio", (array) $user);
        // Retornar DTO usando la entidad con ID asignado
        return UserDTO::fromDomain($user);
    }
}
