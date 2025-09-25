<?php

namespace Infrastructure\Catalog\Repositories;

use Domain\Catalog\Entities\Attribute;
use Domain\Catalog\Repositories\AttributeRepository;
use Domain\Catalog\ValueObjects\AttributeId;
use Domain\Catalog\ValueObjects\AttributeName;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

final class MySQLAttributeRepository implements AttributeRepository
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(Attribute $attribute): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $sql = "INSERT INTO attributes (id, name, created_at, updated_at)
                VALUES (:id, :name, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    updated_at = VALUES(updated_at)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $attribute->id()->value(),
                ':name' => $attribute->name()->value(),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error en AttributeRepository::save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(AttributeId $id): ?Attribute
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attributes WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Attribute(
            new AttributeId($row['id']),
            new AttributeName($row['name'])
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM attributes");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $attributes = [];
        foreach ($rows as $row) {
            $attributes[] = new Attribute(
                new AttributeId($row['id']),
                new AttributeName($row['name'])
            );
        }
        return $attributes;
    }

    public function delete(AttributeId $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM attributes WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
    }
}
