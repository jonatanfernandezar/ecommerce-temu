<?php

namespace Application\UserManagement\Commands;

final class LoginUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}
