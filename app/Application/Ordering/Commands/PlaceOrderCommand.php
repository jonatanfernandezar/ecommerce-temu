<?php

namespace Application\Ordering\Commands;

/**
 * Command to place a new order.
 *
 * @param string $buyerId UserId value (UUID string)
 * @param array<int, array{productId:string,quantity:int,unitPrice:int}> $items
 *        unitPrice in cents (int)
 * @param array{
 *    line1: string,
 *    line2?: string|null,
 *    city: string,
 *    state: string,
 *    postalCode: string,
 *    country?: string
 * } $shippingAddress
 * @param string|null $paymentMethodId optional payment token / method id
 * @param string $currency 3-letter currency code, default 'USD'
 */
final class PlaceOrderCommand
{
    /**
     * @param array<int, array{productId:string,quantity:int,unitPrice:int}> $items
     * @param array{
     *    line1: string,
     *    line2?: string|null,
     *    city: string,
     *    state: string,
     *    postalCode: string,
     *    country?: string
     * } $shippingAddress
     */
    public function __construct(
        public readonly string $buyerId,
        public readonly array $items,
        public readonly array $shippingAddress,
        public readonly ?string $paymentMethodId = null,
        public readonly string $currency = 'USD'
    ) {}
}
