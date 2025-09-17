<?php

namespace Infrastructure\UserManagement\Repositories;

use Domain\UserManagement\ValueObjects\Name;
use Domain\UserManagement\Entities\User;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(User $user): void
    {
        $pdo = $this->connection::getConnection();

        $sql = "INSERT INTO users (id, name, email, password, role, status)
        VALUES (:id, :name, :email, :password, :role, :status)
        ON DUPLICATE KEY UPDATE
            name = :name,
            email = :email,
            password = :password,
            role = :role,
            status = :status";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id'       => $user->id()->value(),
            ':name'     => $user->name()->value(),
            ':email'    => $user->email()->value(),
            ':password' => $user->password()->hash(),
            ':role'     => $user->role()->value(),
            ':status'   => $user->status()->value(),
        ]);
    }

    public function findById(UserId $id): ?User
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new User(
            new UserId($row['id']),
            new Name($row['name']),
            new Email($row['email']),
            new \Domain\UserManagement\ValueObjects\Password($row['password']), // Si guardaste hashed, adapta constructor
            new \Domain\UserManagement\ValueObjects\UserRole($row['role']),
            new \Domain\UserManagement\ValueObjects\UserStatus($row['status'])
        );
    }

    public function findByEmail(Email $email): ?User
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new User(
            new UserId($row['id']),
            new Name($row['name']),
            new Email($row['email']),
            new \Domain\UserManagement\ValueObjects\Password($row['password']),
            new \Domain\UserManagement\ValueObjects\UserRole($row['role']),
            new \Domain\UserManagement\ValueObjects\UserStatus($row['status'])
        );
    }

    public function delete(User $user): void
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $user->id()->value()]);
    }
}
