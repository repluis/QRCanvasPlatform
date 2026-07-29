<?php

namespace Src\Auth\Infrastructure\Repositories;

use Illuminate\Support\Facades\Auth;
use Src\Auth\Application\DTOs\LoginUserDTO;
use Src\Auth\Domain\Repositories\AuthenticationRepositoryInterface;

class LaravelAuthenticationRepository implements AuthenticationRepositoryInterface
{
    public function attempt(LoginUserDTO $credentials): bool
    {
        return Auth::attempt([
            'email' => $credentials->email,
            'password' => $credentials->password,
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
