<?php

namespace Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Domain\UserManagement\Entities\User as DomainUser;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\ValueObjects\Password;
use Domain\UserManagement\ValueObjects\UserRole;
use Domain\UserManagement\ValueObjects\UserStatus;
use Domain\UserManagement\ValueObjects\Name;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Convierte el modelo Eloquent a la Entidad de Dominio.
     */
    public function toDomain(): DomainUser
    {
        return new DomainUser(
            new UserId($this->id),
            new Name($this->name),
            new Email($this->email),
            Password::fromHash($this->password), // hashed
            new UserRole($this->role),
            $this->status === 'active' ? UserStatus::active() : UserStatus::blocked()
        );
    }

    /**
     * Crea un modelo Eloquent a partir de la Entidad de Dominio.
     */
    public static function fromDomain(DomainUser $user): self
    {
        $eloquentUser = new self();
        $eloquentUser->id = $user->id()->value();
        $eloquentUser->name = $user->name()->value();
        $eloquentUser->email = $user->email()->value();
        $eloquentUser->password = $user->password()->hash();
        $eloquentUser->role = $user->role()->value();
        $eloquentUser->status = $user->status()->value();

        return $eloquentUser;
    }
}
