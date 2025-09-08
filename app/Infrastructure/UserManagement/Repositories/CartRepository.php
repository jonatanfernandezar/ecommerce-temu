<?php

namespace Infrastructure\UserManagement\Repositories;

use Domain\UserManagement\Entities\ShoppingCart;
use Domain\UserManagement\Entities\CartItem;
use Domain\UserManagement\ValueObjects\CartId;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\UserManagement\Repositories\CartRepositoryInterface;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;

final class CartRepository implements CartRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(ShoppingCart $cart): void
    {
        $pdo = $this->connection::getConnection();

        try {
            $pdo->beginTransaction();

            // Guardar el carrito
            $stmt = $pdo->prepare("
                INSERT INTO shopping_carts (id, user_id, updated_at)
                VALUES (:id, :user_id, :updated_at)
                ON DUPLICATE KEY UPDATE
                    updated_at = :updated_at
            ");
            $stmt->execute([
                ':id' => $cart->id()->value(),
                ':user_id' => $cart->userId()->value(),
                ':updated_at' => $cart->updatedAt()->format('Y-m-d H:i:s')
            ]);

            // Eliminar items antiguos
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
            $stmt->execute([':cart_id' => $cart->id()->value()]);

            // Insertar items
            $stmt = $pdo->prepare("
                INSERT INTO cart_items (cart_id, product_id, quantity, unit_price)
                VALUES (:cart_id, :product_id, :quantity, :unit_price)
            ");
            foreach ($cart->items() as $item) {
                $stmt->execute([
                    ':cart_id' => $cart->id()->value(),
                    ':product_id' => $item->productId()->value(),
                    ':quantity' => $item->quantity()->value(),
                    ':unit_price' => $item->unitPrice()->amount()
                ]);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function findById(CartId $id): ?ShoppingCart
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM shopping_carts WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $cartRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$cartRow) return null;

        // Obtener items
        $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id");
        $stmt->execute([':cart_id' => $id->value()]);
        $itemsRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $items = [];
        foreach ($itemsRows as $row) {
            $items[] = new CartItem(
                new \Domain\Catalog\ValueObjects\ProductId($row['product_id']),
                new \Domain\Shared\ValueObjects\Quantity((int)$row['quantity']),
                new \Domain\Shared\ValueObjects\Money((float)$row['unit_price'], 'USD') // moneda fija, puedes adaptar
            );
        }

        return new ShoppingCart(
            new CartId($cartRow['id']),
            new UserId($cartRow['user_id']),
            $items
        );
    }

    public function findByUser(UserId $userId): ?ShoppingCart
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM shopping_carts WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return $this->findById(new CartId($row['id']));
    }

    public function delete(ShoppingCart $cart): void
    {
        $pdo = $this->connection::getConnection();

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
            $stmt->execute([':cart_id' => $cart->id()->value()]);

            $stmt = $pdo->prepare("DELETE FROM shopping_carts WHERE id = :id");
            $stmt->execute([':id' => $cart->id()->value()]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
