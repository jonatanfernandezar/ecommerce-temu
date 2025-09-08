<?php

namespace Infrastructure\Seller\Repositories;

use Domain\Seller\Entities\Seller;
use Domain\Seller\ValueObjects\SellerId;
use Domain\Seller\ValueObjects\SellerName;
use Domain\Seller\ValueObjects\SellerEmail;
use Domain\Seller\ValueObjects\SellerStatus;
use Domain\Seller\Repositories\SellerRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;

final class SellerRepository implements SellerRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(Seller $seller): void
    {
        $pdo = $this->connection::getConnection();

        try {
            $sql = "INSERT INTO sellers (id, name, email, status)
                    VALUES (:id, :name, :email, :status)
                    ON DUPLICATE KEY UPDATE
                        name = :name,
                        email = :email,
                        status = :status";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $seller->id()->value(),
                ':name' => $seller->name()->value(),
                ':email' => $seller->email()->value(),
                ':status' => $seller->status()->value(),
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function findById(SellerId $id): ?Seller
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM sellers WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Seller(
            new SellerId($row['id']),
            new SellerName($row['name']),
            new SellerEmail($row['email']),
            new SellerStatus($row['status'])
        );
    }

    public function delete(SellerId $id): void
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("DELETE FROM sellers WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
    }
}
