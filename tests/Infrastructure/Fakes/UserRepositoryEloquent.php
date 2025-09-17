<?php

namespace Tests\Infrastructure\Fakes;

use Domain\UserManagement\Entities\User;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\ValueObjects\UserId;
use Infrastructure\Eloquent\Models\User as EloquentUser;

final class UserRepositoryEloquent implements UserRepositoryInterface
{
    public function save(User $user): void
    {
        EloquentUser::updateOrCreate(
            ['id' => $user->id()->value()],
            [
                'name'    => $user->name()->value(),
                'email'    => $user->email()->value(),
                'password' => $user->password()->hash(),
                'role'     => $user->role()->value(),
                'status'   => $user->status()->value(),
            ]
        );
    }

    public function findById(UserId $id): ?User
    {
        $eloquentUser = EloquentUser::find($id->value());
        if (!$eloquentUser) return null;

        return $eloquentUser->toDomain(); // hacemos un mapper
    }

    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->value())->first();
        if (!$eloquentUser) return null;

        return $eloquentUser->toDomain();
    }

    public function delete(User $user): void
    {
        EloquentUser::destroy($user->id()->value());
    }
}
