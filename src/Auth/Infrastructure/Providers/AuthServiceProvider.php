<?php

namespace Src\Auth\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Auth\Application\Actions\LoginUserAction;
use Src\Auth\Application\Actions\LogoutUserAction;
use Src\Auth\Application\Services\AuthenticationService;
use Src\Auth\Domain\Repositories\AuthenticationRepositoryInterface;
use Src\Auth\Infrastructure\Repositories\LaravelAuthenticationRepository;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthenticationRepositoryInterface::class,
            LaravelAuthenticationRepository::class,
        );

        $this->app->bind(LoginUserAction::class);
        $this->app->bind(LogoutUserAction::class);
        $this->app->bind(RegisterUserAction::class);

        $this->app->bind(AuthenticationService::class, function ($app) {
            return new AuthenticationService(
                $app->make(AuthenticationRepositoryInterface::class),
            );
        });
    }
}
