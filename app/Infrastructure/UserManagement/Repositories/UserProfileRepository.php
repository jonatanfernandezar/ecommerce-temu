<?php

namespace Infrastructure\UserManagement\Repositories;

use Domain\UserManagement\Entities\UserProfile;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Repositories\UserProfileRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;

final class UserProfileRepository implements UserProfileRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(UserProfile $profile): void
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("
            INSERT INTO user_profiles (user_id, name, email, address, phone)
            VALUES (:user_id, :name, :email, :address, :phone)
            ON DUPLICATE KEY UPDATE
                name = :name,
                email = :email,
                address = :address,
                phone = :phone
        ");

        $stmt->execute([
            ':user_id' => $profile->getUserId()->value(),
            ':name' => $profile->getName(),
            ':email' => $profile->getEmail(),
            ':address' => $profile->getAddress(),
            ':phone' => $profile->getPhone()
        ]);
    }

    public function findByUserId(UserId $userId): ?UserProfile
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new UserProfile(
            new UserId($row['user_id']),
            $row['name'],
            $row['email'],
            $row['address'] ?? null,
            $row['phone'] ?? null
        );
    }

    public function delete(UserProfile $profile): void
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("DELETE FROM user_profiles WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $profile->getUserId()->value()]);
    }
}
