<?php

namespace Application\UserManagement\Services;

use Application\UserManagement\DTO\OrderDTO;
use Domain\Ordering\Repositories\OrderRepository;
use Domain\UserManagement\ValueObjects\UserId;

final class ViewOrderHistoryService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    /**
     * @return OrderDTO[]
     */
    public function execute(UserId $userId): array
    {
        $orders = $this->orderRepository->findByBuyer($userId->value());

        return array_map(
            fn($order) => OrderDTO::fromDomain($order),
            $orders
        );
    }
}
