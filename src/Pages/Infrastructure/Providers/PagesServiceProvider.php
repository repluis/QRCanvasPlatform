<?php

namespace Src\Pages\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Pages\Domain\Repositories\PageRepositoryInterface;
use Src\Pages\Infrastructure\Persistence\Repositories\PageRepository;

class PagesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PageRepositoryInterface::class,
            PageRepository::class,
        );
    }
}
