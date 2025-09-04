<?php

namespace Domain\Ordering\Repositories;

use Domain\Ordering\Entities\Payment;
use Domain\Ordering\ValueObjects\PaymentId;

interface PaymentRepositoryInterface
{
    public function save(Payment $payment): void;
    public function findById(PaymentId $id): ?Payment;
}
