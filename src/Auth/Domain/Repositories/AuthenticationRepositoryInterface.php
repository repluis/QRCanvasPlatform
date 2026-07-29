<?php

namespace Src\Auth\Domain\Repositories;

use Src\Auth\Application\DTOs\LoginUserDTO;

interface AuthenticationRepositoryInterface
{
    public function attempt(LoginUserDTO $credentials): bool;

    public function logout(): void;
}
