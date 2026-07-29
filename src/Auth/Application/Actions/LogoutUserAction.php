<?php

namespace Src\Auth\Application\Actions;

use Src\Auth\Application\Services\AuthenticationService;

class LogoutUserAction
{
    public function __construct(
        private readonly AuthenticationService $authService,
    ) {}

    public function execute(): void
    {
        $this->authService->logout();
    }
}
