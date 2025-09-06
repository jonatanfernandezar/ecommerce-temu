<?php

namespace Application\UserManagement\Commands;

final class RegisterUserCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role // "buyer" | "seller"
    ) {}
}
