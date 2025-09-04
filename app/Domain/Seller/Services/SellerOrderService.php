<?php

namespace Domain\Seller\Services;

use Domain\Ordering\Entities\Order;
use Domain\Seller\Events\SellerOrderAccepted;
use Domain\Seller\Events\SellerOrderRejected;
use Domain\Seller\Events\SellerOrderShipped;

class SellerOrderService
{
    public function acceptOrder(Order $order): void
    {
        // Reglas de negocio para aceptar orden
        // ...
        event(new SellerOrderAccepted($order));
    }

    public function rejectOrder(Order $order): void
    {
        // Reglas de negocio para rechazar orden
        // ...
        event(new SellerOrderRejected($order));
    }

    public function markAsShipped(Order $order): void
    {
        // Reglas de negocio para marcar como enviado
        // ...
        event(new SellerOrderShipped($order));
    }
}
