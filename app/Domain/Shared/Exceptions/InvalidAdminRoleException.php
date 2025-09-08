<?php

namespace Domain\Shared\Exceptions;

class InvalidAdminRoleException extends DomainException
{
    public function __construct(string $value)
    {
        parent::__construct("Invalid admin role: {$value}");
    }
}
