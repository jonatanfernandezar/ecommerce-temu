<?php

namespace Application\Ordering\Services;

use Application\Ordering\Commands\PlaceOrderCommand;
use Application\Ordering\DTO\OrderDTO;
use Domain\Ordering\Repositories\OrderRepository;
use Domain\Ordering\ValueObjects\OrderId;
use Domain\Ordering\ValueObjects\OrderItemId;
use Domain\Ordering\ValueObjects\OrderStatus;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Shared\ValueObjects\Money;
use Domain\Shared\ValueObjects\Address;
use Domain\Ordering\Entities\Order;

/**
 * Application service for placing orders.
 */
final class PlaceOrderService
{
    public function __construct(
        private OrderRepository $orders,
        private ProcessPaymentService $payments // injected even if dummy
    ) {}

    /**
     * @param PlaceOrderCommand $cmd
     * @return OrderDTO
     * @throws \InvalidArgumentException
     */
    public function execute(PlaceOrderCommand $cmd): OrderDTO
    {
        // Validate items structure quickly
        if (!is_array($cmd->items) || count($cmd->items) === 0) {
            throw new \InvalidArgumentException('Order must contain at least one item.');
        }

        // Create VOs
        $buyerId = new UserId($cmd->buyerId);

        // Build Address from array
        $addrArr = $cmd->shippingAddress;
        $address = new Address(
            $addrArr['line1'],
            $addrArr['line2'] ?? null,
            $addrArr['city'],
            $addrArr['state'],
            $addrArr['postalCode'],
            $addrArr['country'] ?? 'US'
        );

        // Create order aggregate
        $order = Order::place($buyerId, $address, $cmd->currency);

        // Add items (expect item.unitPrice in cents int)
        foreach ($cmd->items as $rawItem) {
            if (!isset($rawItem['productId'], $rawItem['quantity'], $rawItem['unitPrice'])) {
                throw new \InvalidArgumentException('Each item must include productId, quantity and unitPrice (cents).');
            }

            $productId = new ProductId($rawItem['productId']);
            $quantity = new Quantity((int) $rawItem['quantity']);
            $unitPrice = new Money((int) $rawItem['unitPrice'], $cmd->currency);

            $order->addItem($productId, $quantity, $unitPrice);
        }

        // Persist as PENDING
        $this->orders->save($order);

        // If payment method provided, process payment synchronously (dummy)
        if ($cmd->paymentMethodId !== null) {
            $paymentResult = $this->payments->process($cmd->paymentMethodId, $order->total()->amount(), $order->total()->currency());
            if ($paymentResult->success) {
                $order->pay();
                $this->orders->save($order);
            } else {
                // leave as pending (or implement order->cancel() depending on business)
            }
        }

        return OrderDTO::fromDomain($order);
    }
}
