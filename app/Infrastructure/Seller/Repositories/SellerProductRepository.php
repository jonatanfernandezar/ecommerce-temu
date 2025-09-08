<?php

namespace Infrastructure\Seller\Repositories;

use Domain\Seller\Entities\SellerProduct;
use Domain\Seller\ValueObjects\SellerId;
use Domain\Seller\Repositories\SellerProductRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;

final class SellerProductRepository implements SellerProductRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function addProduct(SellerProduct $product): void
    {
        $pdo = $this->connection::getConnection();

        try {
            $sql = "INSERT INTO seller_products (seller_id, product_id)
                    VALUES (:seller_id, :product_id)
                    ON DUPLICATE KEY UPDATE seller_id = :seller_id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':seller_id' => $product->sellerId()->value(),
                ':product_id' => $product->productId()->value(),
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateProduct(SellerProduct $product): void
    {
        // Normalmente no hay campos a actualizar, dejamos como addProduct
        $this->addProduct($product);
    }

    public function removeProduct(SellerProduct $product): void
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("DELETE FROM seller_products WHERE seller_id = :seller_id AND product_id = :product_id");
        $stmt->execute([
            ':seller_id' => $product->sellerId()->value(),
            ':product_id' => $product->productId()->value(),
        ]);
    }

    /** @return SellerProduct[] */
    public function getProductsBySeller(SellerId $sellerId): array
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM seller_products WHERE seller_id = :seller_id");
        $stmt->execute([':seller_id' => $sellerId->value()]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $products = [];
        foreach ($rows as $row) {
            $products[] = new SellerProduct(
                new SellerId($row['seller_id']),
                new \Domain\Catalog\ValueObjects\ProductId($row['product_id'])
            );
        }

        return $products;
    }
}
