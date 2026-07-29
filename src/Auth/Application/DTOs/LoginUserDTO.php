<?php

namespace Src\Auth\Application\DTOs;

class LoginUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(array $credentials): self
    {
        return new self(
            email: $credentials['email'],
            password: $credentials['password'],
        );
    }
}
