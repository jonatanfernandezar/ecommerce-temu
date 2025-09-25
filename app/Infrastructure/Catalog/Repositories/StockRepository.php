<?php

namespace Infrastructure\Catalog\Repositories;

use Domain\Catalog\Entities\Stock;
use Domain\Catalog\Repositories\StockRepository;
use Domain\Catalog\ValueObjects\StockId;
//use Domain\Shared\ValueObjects\Quantity;
use Domain\Catalog\ValueObjects\Quantity;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

final class MySQLStockRepository implements StockRepository
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(Stock $stock): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $sql = "INSERT INTO stock (id, product_id, quantity, created_at, updated_at)
                VALUES (:id, :product_id, :quantity, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    quantity = VALUES(quantity),
                    updated_at = VALUES(updated_at)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $stock->id()->value(),
                ':product_id' => $stock->productId()->value(),
                ':quantity' => $stock->quantity()->value(),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error en StockRepository::save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(StockId $id): ?Stock
    {
        $stmt = $this->pdo->prepare("SELECT * FROM stock WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Stock(
            new StockId($row['id']),
            new \Domain\Catalog\ValueObjects\ProductId($row['product_id']),
            new Quantity((int)$row['quantity'])
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM stock");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stocks = [];
        foreach ($rows as $row) {
            $stocks[] = new Stock(
                new StockId($row['id']),
                new \Domain\Catalog\ValueObjects\ProductId($row['product_id']),
                new Quantity((int)$row['quantity'])
            );
        }
        return $stocks;
    }

    public function delete(StockId $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM stock WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
    }
}
