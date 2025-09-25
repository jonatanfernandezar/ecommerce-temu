<?php

namespace Infrastructure\Catalog\Repositories;

use App\Domain\Catalog\Entities\Product;
use App\Domain\Catalog\ValueObjects\Price;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\BrandId;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Catalog\ValueObjects\ProductStatus;
use Infrastructure\Shared\Persistence\MySQLConnection;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

final class ProductRepository implements ProductRepositoryInterface
{
    private PDO $pdo;

    public function __construct(MySQLConnection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    public function save(Product $product): void
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $sql = "INSERT INTO products 
                (id, name, description, price, stock, category_id, brand_id, status, created_at, updated_at)
                VALUES (:id, :name, :description, :price, :stock, :category_id, :brand_id, :status, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    description = VALUES(description),
                    price = VALUES(price),
                    stock = VALUES(stock),
                    category_id = VALUES(category_id),
                    brand_id = VALUES(brand_id),
                    status = VALUES(status),
                    updated_at = VALUES(updated_at)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id'          => $product->getId()->value(),
                ':name'        => $product->getName(),
                ':description' => $product->getDescription(),
                ':price'       => $product->getPrice()->amount(),
                ':stock'       => $product->getStock(),
                ':category_id' => $product->getCategoryId()->value(),
                ':brand_id'    => $product->getBrandId()?->value(),
                ':status'      => $product->status()->value(),
                ':created_at'  => $now,
                ':updated_at'  => $now,
            ]);
        } catch (PDOException $e) {
            Log::error("❌ Error en ProductRepository::save(): " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(ProductId $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Product(
            new ProductId($row['id']),
            $row['name'],
            $row['description'],
            new Price($row['price']),
            new Quantity((int)$row['stock']),
            new CategoryId($row['category_id']),
            $row['brand_id'] ? new BrandId($row['brand_id']) : null,
            [], // attributes vacíos por ahora
            ProductStatus::active() // o mapear desde $row['status'] si quieres
        );
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product(
                new ProductId($row['id']),
                $row['name'],
                $row['description'],
                new Price($row['price']),
                new Quantity((int)$row['stock']),
                new CategoryId($row['category_id']),
                $row['brand_id'] ? new BrandId($row['brand_id']) : null,
                [],
                ProductStatus::active() // o mapear desde $row['status']
            );
        }

        return $products;
    }
}
