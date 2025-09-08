<?php

namespace Infrastructure\Seller\Repositories;

use Domain\Seller\Entities\SellerProfile;
use Domain\Seller\ValueObjects\SellerId;
use Domain\Seller\Repositories\SellerProfileRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;

final class SellerProfileRepository implements SellerProfileRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(SellerProfile $profile): void
    {
        $pdo = $this->connection::getConnection();

        try {
            $sql = "INSERT INTO seller_profiles (seller_id, address, phone, description)
                    VALUES (:seller_id, :address, :phone, :description)
                    ON DUPLICATE KEY UPDATE
                        address = :address,
                        phone = :phone,
                        description = :description";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':seller_id' => $profile->sellerId()->value(),
                ':address' => $profile->address(),
                ':phone' => $profile->phone(),
                ':description' => $profile->description(),
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function findById(SellerId $sellerId): ?SellerProfile
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM seller_profiles WHERE seller_id = :seller_id");
        $stmt->execute([':seller_id' => $sellerId->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new SellerProfile(
            new SellerId($row['seller_id']),
            $row['address'],
            $row['phone'],
            $row['description']
        );
    }
}
