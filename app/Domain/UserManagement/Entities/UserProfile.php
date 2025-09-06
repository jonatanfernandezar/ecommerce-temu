<?php

namespace Domain\UserManagement\Entities;

use Domain\UserManagement\ValueObjects\UserId;

class UserProfile
{
    public function __construct(
        private UserId $userId,
        private string $name,
        private string $email,
        private ?string $address = null,
        private ?string $phone = null
    ) {}

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function updateProfile(string $name, string $email, ?string $address, ?string $phone): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->phone = $phone;
    }
}
