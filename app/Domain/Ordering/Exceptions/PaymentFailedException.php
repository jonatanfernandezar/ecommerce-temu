<?php

namespace Domain\Ordering\Exceptions;

use Domain\Shared\Exceptions\DomainException;

class PaymentFailedException extends DomainException
{
    public function __construct(string $message = "The payment could not be processed.", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
