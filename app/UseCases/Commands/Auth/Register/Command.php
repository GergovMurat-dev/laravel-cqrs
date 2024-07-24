<?php

namespace App\UseCases\Commands\Auth\Register;

class Command
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null
    )
    {
    }

    public static function fillAttributes(array $properties): self
    {
        return new self(
            name: $properties['name'] ?? null,
            email: $properties['email'] ?? null,
            password: $properties['password'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password
        ];
    }
}