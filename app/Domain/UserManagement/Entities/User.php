<?php

namespace Domain\UserManagement\Entities;

use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\ValueObjects\Password;
use Domain\UserManagement\ValueObjects\UserRole;
use Domain\UserManagement\ValueObjects\UserStatus;
use Domain\UserManagement\ValueObjects\Name;

final class User
{
    private ?UserId $id;
    private Name $name;
    private Email $email;
    private Password $password;
    private UserRole $role;
    private UserStatus $status;

    public function __construct(
        ?UserId $id,
        Name $name,
        Email $email,
        Password $password,
        UserRole $role,
        ?UserStatus $status = null
    ) {
        $this->id       = $id;
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
        $this->status   = $status ?? UserStatus::active();
    }

    public function id(): ?UserId { return $this->id; }
    public function name(): Name { return $this->name; }
    public function email(): Email { return $this->email; }
    public function password(): Password { return $this->password; }
    public function role(): UserRole { return $this->role; }
    public function status(): UserStatus { return $this->status; }

    public function changeRole(UserRole $newRole): void
    {
        $this->role = $newRole;
        // Posible: disparar un UserRoleChangedEvent
    }

    public function block(?string $reason = null): void
    {
        if ($this->status->isBlocked()) {
            return; // ya bloqueado, evitamos lógica duplicada
        }

        $this->status = UserStatus::blocked();

        // Aquí podrías guardar el motivo en otra entidad/tabla si lo necesitas
        // o disparar un evento UserBlockedEvent con ese motivo
    }

    public function assignId(UserId $id): void
    {
        if ($this->id !== null) {
            throw new \LogicException("El ID ya fue asignado.");
        }
        $this->id = $id;
    }

    public static function register(
        Name $name,
        Email $email,
        Password $password,
        UserRole $role
    ): self {
        $user = new self(UserId::generate(), $name, $email, $password, $role, UserStatus::active());
        return $user;
    }
}