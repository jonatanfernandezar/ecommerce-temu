<?php

namespace Infrastructure\Eloquent\Repositories;

use Domain\UserManagement\Entities\User;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\Email;
use Infrastructure\Eloquent\Models\User as EloquentUser;

final class UserRepositoryEloquent implements UserRepositoryInterface
{
    public function save(User $user): void
    {
        if ($user->id() === null) {
            // Crear nuevo usuario
            $eloquentUser = EloquentUser::create([
                'name'     => $user->name()->value(),
                'email'    => $user->email()->value(),
                'password' => $user->password()->hash(),
                'role'     => $user->role()->value(),
                'status'   => $user->status()->value(),
            ]);

            // Asignar ID generado al dominio
            $user->assignId(new UserId((string) $eloquentUser->id));
        } else {
            // Actualizar usuario existente
            $eloquentUser = EloquentUser::updateOrCreate(
                ['id' => $user->id()->value()],
                [
                    'name'     => $user->name()->value(),
                    'email'    => $user->email()->value(),
                    'password' => $user->password()->hash(),
                    'role'     => $user->role()->value(),
                    'status'   => $user->status()->value(),
                ]
            );
        }
    }

    public function findById(UserId $id): ?User
    {
        $eloquentUser = EloquentUser::find($id->value());
        return $eloquentUser?->toDomain();
    }

    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->value())->first();
        if (!$eloquentUser) return null;

        $user = $eloquentUser->toDomain();

        // Sobrescribir el Password para usar hash de DB
        $user = new User(
            $user->id(),
            $user->name(),
            $user->email(),
            \Domain\UserManagement\ValueObjects\Password::fromHash($eloquentUser->password),
            $user->role(),
            $user->status()
        );

        return $user;
    }

    public function delete(User $user): void
    {
        EloquentUser::destroy($user->id()->value());
    }
}
