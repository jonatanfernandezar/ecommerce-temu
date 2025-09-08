<?php

namespace Domain\Shared\Exceptions;

class InvalidAdminIdException extends DomainException
{
    public function __construct(string $value)
    {
        parent::__construct("Invalid AdminId: {$value}");
    }
}
