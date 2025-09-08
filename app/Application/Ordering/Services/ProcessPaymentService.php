<?php

namespace Application\Ordering\Services;

use Application\Ordering\DTO\PaymentResult;

/**
 * Simple synchronous payment processor stub.
 * Replace implementation with real gateway in Infrastructure later.
 */
final class ProcessPaymentService
{
    public function __construct(
        // If later you want to inject a gateway, add it here as interface
    ) {}

    /**
     * Process a payment.
     *
     * @param string $paymentMethodId optional token / method id
     * @param int $amount in cents
     * @param string $currency
     * @return PaymentResult
     */
    public function process(?string $paymentMethodId, int $amount, string $currency): PaymentResult
    {
        // Dummy behavior: succeed if amount > 0
        if ($amount <= 0) {
            return new PaymentResult(false, null, null, ['reason' => 'invalid_amount']);
        }

        // In future: call injected PaymentGatewayInterface
        return new PaymentResult(true, uniqid('tx_', true), 'dummy', []);
    }
}
