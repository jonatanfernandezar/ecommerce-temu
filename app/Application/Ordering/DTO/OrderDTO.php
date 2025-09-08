<?php

namespace Application\Ordering\DTO;

use DateTimeImmutable;

/**
 * @param OrderItemDTO[] $items
 */
final class OrderDTO
{
    /**
     * @param OrderItemDTO[] $items
     */
    public function __construct(
        public readonly string $id,
        public readonly string $buyerId,
        public readonly string $status,
        public readonly int $totalAmount, // cents
        public readonly string $currency,
        public readonly array $items,
        public readonly string $shippingLine1,
        public readonly ?string $shippingLine2,
        public readonly string $shippingCity,
        public readonly string $shippingState,
        public readonly string $shippingPostalCode,
        public readonly string $shippingCountry,
        public readonly string $createdAtIso,
        public readonly ?string $paidAtIso = null,
        public readonly ?string $shippedAtIso = null,
        public readonly ?string $deliveredAtIso = null
    ) {}

    /**
     * Map from domain Order to DTO.
     * We receive domain types but will extract primitives.
     *
     * @param \Domain\Ordering\Entities\Order $order
     */
    public static function fromDomain(\Domain\Ordering\Entities\Order $order): self
    {
        $itemsDto = [];
        foreach ($order->items() as $item) {
            $unitPrice = $item->unitPrice(); // Money
            $lineTotal = $item->lineTotal();

            $itemsDto[] = new OrderItemDTO(
                (string) $item->id()->value(),
                (string) $item->productId()->value(),
                $item->quantity()->value(),
                $unitPrice->amount(),
                $lineTotal->amount()
            );
        }

        $total = $order->total();

        $paidAt = $order->paidAt();
        $shippedAt = $order->shippedAt();
        $deliveredAt = $order->deliveredAt();

        return new self(
            (string) $order->id()->value(),
            (string) $order->buyerId()->value(),
            $order->status()->value(),
            $total->amount(),
            $total->currency(),
            $itemsDto,
            $order->shippingAddress()->line1(),
            $order->shippingAddress()->line2(),
            $order->shippingAddress()->city(),
            $order->shippingAddress()->state(),
            $order->shippingAddress()->postalCode(),
            $order->shippingAddress()->country(),
            $order->createdAt()->format(\DateTime::ATOM),
            $paidAt ? $paidAt->format(\DateTime::ATOM) : null,
            $shippedAt ? $shippedAt->format(\DateTime::ATOM) : null,
            $deliveredAt ? $deliveredAt->format(\DateTime::ATOM) : null
        );
    }
}
