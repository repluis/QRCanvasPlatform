<?php

namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\LoginUserDTO;
use Src\Auth\Application\Services\AuthenticationService;

class LoginUserAction
{
    public function __construct(
        private readonly AuthenticationService $authService,
    ) {}

    public function execute(LoginUserDTO $credentials): bool
    {
        return $this->authService->authenticate($credentials);
    }
}
