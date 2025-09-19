<?php

namespace Infrastructure\UserManagement\Repositories;

use Domain\UserManagement\ValueObjects\Name;
use Domain\UserManagement\Entities\User;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\ValueObjects\Email;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use Illuminate\Support\Facades\Log;
use Domain\UserManagement\ValueObjects\Password;
use Domain\UserManagement\ValueObjects\UserRole;
use Domain\UserManagement\ValueObjects\UserStatus;
use PDO;
use PDOException;

final class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(User $user): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $sql = "INSERT INTO users (name, email, password, role, status, created_at, updated_at)
            VALUES (:name, :email, :password, :role, :status, :created_at, :updated_at)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                email = VALUES(email),
                password = VALUES(password),
                role = VALUES(role),
                status = VALUES(status),
                updated_at = VALUES(updated_at)";

        try {
            Log::info("📝 Ejecutando query UserRepository::save", [
                'sql' => $sql,
                'params' => [
                    'name'       => $user->name()->value(),
                    'email'      => $user->email()->value(),
                    'password'   => $user->password()->hash(),
                    'role'       => $user->role()->value(),
                    'status'     => $user->status()->value(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name'       => $user->name()->value(),
                ':email'      => $user->email()->value(),
                ':password'   => $user->password()->hash(),
                ':role'       => $user->role()->value(),
                ':status'     => $user->status()->value(),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);

            $lastId = (int) $this->pdo->lastInsertId();
            Log::info("🔑 lastInsertId devuelto por PDO", ['lastId' => $lastId]);

            if ($lastId > 0) {
                $user->assignId(new UserId($lastId));
            }
        } catch (PDOException $e) {
            Log::error("❌ Error en save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(UserId $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new User(
            new UserId($row['id']),
            new Name($row['name']),
            new Email($row['email']),
            Password::fromHash($row['password']), // 👈 CORREGIDO
            UserRole::fromString($row['role']),
            UserStatus::fromString($row['status'])
        );
    }

    public function findByEmail(Email $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email->value()]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new User(
            new UserId($row['id']),
            new Name($row['name']),
            new Email($row['email']),
            Password::fromHash($row['password']), // 👈 CORREGIDO
            UserRole::fromString($row['role']),
            UserStatus::fromString($row['status'])
        );
    }

    public function delete(User $user): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $user->id()->value()]);
    }
}
