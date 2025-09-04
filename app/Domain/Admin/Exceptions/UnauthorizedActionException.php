<?php

namespace Domain\Admin\Exceptions;

class UnauthorizedActionException extends AdminDomainException
{
    public function __construct(string $message = "Unauthorized action")
    {
        parent::__construct($message);
    }
}
