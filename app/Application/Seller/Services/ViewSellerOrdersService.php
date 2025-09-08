<?php

namespace Application\Seller\Services;

use Application\UserManagement\DTO\OrderDTO;
use Domain\Ordering\Repositories\OrderRepository;

final class ViewSellerOrdersService
{
    public function __construct(private OrderRepository $orders) {}

    /** @return OrderDTO[] */
    public function execute(string $sellerId): array
    {
        // ⚠️ Necesitas ampliar el OrderRepository: findBySellerId($sellerId)
        if (!method_exists($this->orders, 'findBySellerId')) {
            throw new \RuntimeException("OrderRepository must implement findBySellerId");
        }

        $orders = $this->orders->findByBuyer($sellerId);

        return array_map(fn($order) => OrderDTO::fromDomain($order), $orders);
    }
}
