<?php

namespace Src\Auth\Application\Services;

use Src\Auth\Application\DTOs\LoginUserDTO;
use Src\Auth\Domain\Exceptions\InvalidCredentialsException;
use Src\Auth\Domain\Repositories\AuthenticationRepositoryInterface;

class AuthenticationService
{
    public function __construct(
        private readonly AuthenticationRepositoryInterface $authRepository,
    ) {}

    public function authenticate(LoginUserDTO $credentials): bool
    {
        $result = $this->authRepository->attempt($credentials);

        if (!$result) {
            throw new InvalidCredentialsException();
        }

        return true;
    }

    public function logout(): void
    {
        $this->authRepository->logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
