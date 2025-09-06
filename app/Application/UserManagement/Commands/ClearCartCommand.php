<?php

namespace Application\UserManagement\Commands;

final class ClearCartCommand
{
    public function __construct(
        public readonly string $userId
    ) {}
}
