<?php

namespace Infrastructure\Catalog\Repositories;

use Domain\Catalog\Entities\Category;
use Domain\Catalog\Repositories\CategoryRepository as CategoryRepositoryInterface;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\CategoryName;
use Domain\Catalog\ValueObjects\CategorySlug;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

final class CategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(Category $category): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $sql = "INSERT INTO categories (id, name, created_at, updated_at)
                VALUES (:id, :name, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    updated_at = VALUES(updated_at)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $category->id()->value(),
                ':name' => $category->name()->value(),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error en CategoryRepository::save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(CategoryId $id): ?Category
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Category(
            new CategoryId($row['id']),
            new CategoryName($row['name']),
            new CategorySlug($row['slug'] ?? ''),
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Category(
                new CategoryId($row['id']),
                new CategoryName($row['name']),
                new CategorySlug($row['slug'] ?? ''),
            );
        }
        return $categories;
    }

    public function delete(CategoryId $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
    }
}
