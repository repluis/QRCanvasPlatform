<?php

use App\Providers\AppServiceProvider;
use Src\Auth\Infrastructure\Providers\AuthServiceProvider;
use Src\Pages\Infrastructure\Providers\PagesServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    PagesServiceProvider::class,
];
