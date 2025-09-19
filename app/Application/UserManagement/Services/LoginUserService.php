<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\Commands\LoginUserCommand;
use Application\UserManagement\DTO\UserDTO;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\Exceptions\UserDomainException;
use Domain\UserManagement\ValueObjects\Email;
use Firebase\JWT\JWT;

final class LoginUserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(LoginUserCommand $command): array
    {
        $user = $this->userRepository->findByEmail(new Email($command->email));

        if (!$user || !$user->password()->verify($command->password)) {
            throw new UserDomainException("Invalid credentials");
        }

        // Cargar config JWT
        $secret = config('jwt.secret');
        $algo   = config('jwt.algo', 'HS256');
        $ttl    = (int) config('jwt.ttl', 60); // minutos

        $payload = [
            'sub'   => $user->id()->value(),
            'email' => $user->email()->value(),
            'role'  => $user->role()->value(),
            'iat'   => time(),
            'exp'   => time() + ($ttl * 60), // convertir minutos a segundos
        ];

        $jwt = JWT::encode($payload, $secret, $algo);

        return [
            'access_token' => $jwt,
            'token_type'   => 'Bearer',
            'expires_in'   => $ttl * 60, // en segundos
        ];
    }
}
