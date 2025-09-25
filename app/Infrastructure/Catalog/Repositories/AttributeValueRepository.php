<?php

namespace Infrastructure\Catalog\Repositories;

use Domain\Catalog\Entities\AttributeValue;
use Domain\Catalog\Repositories\AttributeValueRepository;
use Domain\Catalog\ValueObjects\AttributeValueId;
use Domain\Catalog\ValueObjects\AttributeValueName;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

final class MySQLAttributeValueRepository implements AttributeValueRepository
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(AttributeValue $value): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $sql = "INSERT INTO attribute_values (id, attribute_id, name, created_at, updated_at)
                VALUES (:id, :attribute_id, :name, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    updated_at = VALUES(updated_at)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $value->id()->value(),
                ':attribute_id' => $value->attributeId()->value(),
                ':name' => $value->value()->value(),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error en AttributeValueRepository::save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(AttributeValueId $id): ?AttributeValue
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attribute_values WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new AttributeValue(
            new AttributeValueId($row['id']),
            new \Domain\Catalog\ValueObjects\AttributeId($row['attribute_id']),
            new AttributeValueName($row['name'])
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM attribute_values");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $values = [];
        foreach ($rows as $row) {
            $values[] = new AttributeValue(
                new AttributeValueId($row['id']),
                new \Domain\Catalog\ValueObjects\AttributeId($row['attribute_id']),
                new AttributeValueName($row['name'])
            );
        }
        return $values;
    }

    public function delete(AttributeValueId $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM attribute_values WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
    }
}
