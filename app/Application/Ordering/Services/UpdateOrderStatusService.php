<?php

namespace Application\Ordering\Services;

use Application\Ordering\Commands\UpdateOrderStatusCommand;
use Application\Ordering\DTO\OrderDTO;
use Domain\Ordering\Repositories\OrderRepository;
use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\OrderStatus;

/**
 * Service that updates order status following domain transitions.
 */
final class UpdateOrderStatusService
{
    public function __construct(private OrderRepository $orders) {}

    public function execute(UpdateOrderStatusCommand $cmd): OrderDTO
    {
        $orderId = new OrderId($cmd->orderId);
        $order = $this->orders->findById($orderId);
        if ($order === null) {
            throw new \RuntimeException("Order not found: {$cmd->orderId}");
        }

        $statusVo = new OrderStatus($cmd->newStatus);

        // Map requested status to domain methods (will enforce allowed transitions)
        switch ($statusVo->value()) {
            case OrderStatus::PAID:
                $order->pay();
                break;
            case OrderStatus::SHIPPED:
                $order->ship();
                break;
            case OrderStatus::DELIVERED:
                $order->deliver();
                break;
            case OrderStatus::CANCELLED:
                $order->cancel();
                break;
            case OrderStatus::PENDING:
                // no-op or re-open? keep no-op to avoid illegal transition
                break;
            default:
                throw new \InvalidArgumentException("Unsupported order status: {$statusVo->value()}");
        }

        $this->orders->save($order);

        return OrderDTO::fromDomain($order);
    }
}
