<?php

namespace Infrastructure\Catalog\Repositories;

use App\Domain\Catalog\Entities\Product;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\ProductStatus;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\BrandId;
use Domain\Shared\ValueObjects\Quantity;
use App\Domain\Catalog\ValueObjects\Price;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;

final class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(Product $product): void
    {
        $pdo = $this->connection::getConnection();
        $sql = "INSERT INTO products
                (id, name, description, price, stock, category_id, brand_id, attributes_json, status)
                VALUES (:id, :name, :description, :price, :stock, :category_id, :brand_id, :attributes_json, :status)
                ON DUPLICATE KEY UPDATE
                    name = :name,
                    description = :description,
                    price = :price,
                    stock = :stock,
                    category_id = :category_id,
                    brand_id = :brand_id,
                    attributes_json = :attributes_json,
                    status = :status";

        $stmt = $this->$pdo->prepare($sql);
        $stmt->execute([
            ':id' => $product->getId()->value(),
            ':name' => $product->getName(),
            ':description' => $product->getDescription(),
            ':price' => $product->getPrice()->amount(),
            ':stock' => $product->getStock(),
            ':category_id' => $product->getCategoryId()->value(),
            ':brand_id' => $product->getBrandId()?->value(),
            ':attributes_json' => json_encode($product->getAttributes(), JSON_THROW_ON_ERROR),
            ':status' => $product->status()->value()
        ]);
    }

    public function findById(ProductId $id): ?Product
    {
        $pdo = $this->connection::getConnection();
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->$pdo->prepare($sql);
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Product(
            new ProductId($row['id']),
            $row['name'],
            $row['description'],
            new Price((float)$row['price']),
            new Quantity((int)$row['stock']),
            new CategoryId($row['category_id']),
            $row['brand_id'] ? new BrandId($row['brand_id']) : null,
            json_decode($row['attributes_json'], true, 512, JSON_THROW_ON_ERROR),
            new ProductStatus($row['status'])
        );
    }

    public function delete(ProductId $id): void
    {
        $pdo = $this->connection::getConnection();
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->$pdo->prepare($sql);
        $stmt->execute([':id' => $id->value()]);
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        $pdo = $this->connection::getConnection();
        $sql = "SELECT * FROM products";
        $stmt = $this->$pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product(
                new ProductId($row['id']),
                $row['name'],
                $row['description'],
                new Price((float)$row['price']),
                new Quantity((int)$row['stock']),
                new CategoryId($row['category_id']),
                $row['brand_id'] ? new BrandId($row['brand_id']) : null,
                json_decode($row['attributes_json'], true, 512, JSON_THROW_ON_ERROR),
                new ProductStatus($row['status'])
            );
        }

        return $products;
    }
}
