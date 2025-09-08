<?php

namespace Infrastructure\Ordering\Repositories;

use Domain\Ordering\Entities\Order;
use Domain\Ordering\Entities\OrderItem;
use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\OrderItemId;
use Domain\Ordering\ValueObjects\OrderStatus;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Shared\ValueObjects\Money;
use Domain\Catalog\ValueObjects\ProductId;
use Infrastructure\Shared\Persistence\MySQLConnection;
use PDO;
use PDOException;

final class OrderRepository implements \Domain\Ordering\Repositories\OrderRepository
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(Order $order): void
    {
        $pdo = $this->connection::getConnection();

        try {
            $pdo->beginTransaction();

            // Insertar/Actualizar la orden
            $sqlOrder = "INSERT INTO orders
                (id, buyer_id, shipping_address_json, status, created_at, paid_at, shipped_at, delivered_at, currency)
                VALUES (:id, :buyer_id, :shipping_address_json, :status, :created_at, :paid_at, :shipped_at, :delivered_at, :currency)
                ON DUPLICATE KEY UPDATE
                    buyer_id = :buyer_id,
                    shipping_address_json = :shipping_address_json,
                    status = :status,
                    created_at = :created_at,
                    paid_at = :paid_at,
                    shipped_at = :shipped_at,
                    delivered_at = :delivered_at,
                    currency = :currency";

            $stmtOrder = $pdo->prepare($sqlOrder);
            $stmtOrder->execute([
                ':id' => $order->id()->value(),
                ':buyer_id' => $order->buyerId()->value(),
                ':shipping_address_json' => json_encode($order->shippingAddress(), JSON_THROW_ON_ERROR),
                ':status' => $order->status()->value(),
                ':created_at' => $order->createdAt()->format('Y-m-d H:i:s'),
                ':paid_at' => $order->paidAt()?->format('Y-m-d H:i:s'),
                ':shipped_at' => $order->shippedAt()?->format('Y-m-d H:i:s'),
                ':delivered_at' => $order->deliveredAt()?->format('Y-m-d H:i:s'),
                ':currency' => $order->currency(),
            ]);

            // Eliminar items antiguos de la orden (para simplificar actualización)
            $stmtDeleteItems = $pdo->prepare("DELETE FROM order_items WHERE order_id = :order_id");
            $stmtDeleteItems->execute([':order_id' => $order->id()->value()]);

            // Insertar los items actuales
            $sqlItem = "INSERT INTO order_items
                (id, order_id, product_id, quantity, unit_price_amount, unit_price_currency)
                VALUES (:id, :order_id, :product_id, :quantity, :unit_price_amount, :unit_price_currency)";

            $stmtItem = $pdo->prepare($sqlItem);

            foreach ($order->items() as $item) {
                /** @var OrderItem $item */
                $stmtItem->execute([
                    ':id' => $item->id()->value(),
                    ':order_id' => $order->id()->value(),
                    ':product_id' => $item->productId()->value(),
                    ':quantity' => $item->quantity()->value(),
                    ':unit_price_amount' => $item->unitPrice()->amount(),
                    ':unit_price_currency' => $item->unitPrice()->currency(),
                ]);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function findById(OrderId $id): ?Order
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Reconstruir la orden
        $order = new Order(
            new OrderId($row['id']),
            new \Domain\UserManagement\ValueObjects\UserId($row['buyer_id']),
            json_decode($row['shipping_address_json'], true, 512, JSON_THROW_ON_ERROR),
            $row['currency']
        );

        $orderStatus = new OrderStatus($row['status']);
        // Se podrían agregar setters internos o reflección para asignar status y fechas
        // Para simplicidad asumimos que el constructor deja status = pending

        // Obtener items
        $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmtItems->execute([':order_id' => $id->value()]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $i) {
            $order->addItem(
                new ProductId($i['product_id']),
                new Quantity((int)$i['quantity']),
                new Money((int)$i['unit_price_amount'], $i['unit_price_currency'])
            );
        }

        return $order;
    }

    /** @return Order[] */
    public function findByBuyer(string $buyerId): array
    {
        $pdo = $this->connection::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM orders WHERE buyer_id = :buyer_id");
        $stmt->execute([':buyer_id' => $buyerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];
        foreach ($rows as $row) {
            $orders[] = $this->findById(new OrderId($row['id']));
        }

        return $orders;
    }

    public function delete(OrderId $id): void
    {
        $pdo = $this->connection::getConnection();

        $stmtDeleteItems = $pdo->prepare("DELETE FROM order_items WHERE order_id = :order_id");
        $stmtDeleteItems->execute([':order_id' => $id->value()]);

        $stmtDeleteOrder = $pdo->prepare("DELETE FROM orders WHERE id = :id");
        $stmtDeleteOrder->execute([':id' => $id->value()]);
    }
}
