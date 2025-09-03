<?php

namespace Domain\UserManagement\Entities;

use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\ValueObjects\Password;
use Domain\UserManagement\ValueObjects\UserRole;
use Domain\UserManagement\Events\UserRegisteredEvent;

final class User
{
    private UserId $id;
    private Email $email;
    private Password $password;
    private UserRole $role;

    public function __construct(UserId $id, Email $email, Password $password, UserRole $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function changeRole(UserRole $newRole): void
    {
        $this->role = $newRole;
        // Podrías disparar UserRoleChangedEvent si quieres notificaciones
    }

    public static function register(Email $email, Password $password, UserRole $role): self
    {
        $user = new self(UserId::generate(), $email, $password, $role);
        // Disparar evento de usuario registrado
        // event(new UserRegisteredEvent($user));
        return $user;
    }
}
