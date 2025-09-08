<?php

namespace Application\Ordering\DTO;

/**
 * Simple result returned by ProcessPaymentService.
 */
final class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $transactionId = null,
        public readonly ?string $provider = null,
        public readonly array $raw = []
    ) {}
}
