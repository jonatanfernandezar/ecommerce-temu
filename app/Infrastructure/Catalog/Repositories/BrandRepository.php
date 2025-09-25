<?php

namespace Infrastructure\Catalog\Repositories;

use Domain\Catalog\Entities\Brand;
use Domain\Catalog\Repositories\BrandRepository as BrandRepositoryInterface;
use Domain\Catalog\ValueObjects\BrandId;
use Domain\Catalog\ValueObjects\BrandName;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

final class BrandRepository implements BrandRepositoryInterface
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(Brand $brand): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $sql = "INSERT INTO brands (id, name, created_at, updated_at)
                VALUES (:id, :name, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    updated_at = VALUES(updated_at)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $brand->id()->value(),
                ':name' => $brand->name()->value(),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error en BrandRepository::save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(BrandId $id): ?Brand
    {
        $stmt = $this->pdo->prepare("SELECT * FROM brands WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Brand(
            new BrandId($row['id']),
            new BrandName($row['name'])
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM brands");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $brands = [];
        foreach ($rows as $row) {
            $brands[] = new Brand(
                new BrandId($row['id']),
                new BrandName($row['name'])
            );
        }
        return $brands;
    }

    public function delete(BrandId $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM brands WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
    }
}
