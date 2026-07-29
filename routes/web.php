<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Src\Pages\Application\Actions\GetUserPagesAction;

Route::get('/', function (GetUserPagesAction $action) {
    $userPages = $action->execute(auth()->id());

    return Inertia::render('Home', [
        'userPages' => array_map(fn ($p) => [
            'uuid' => $p->getUuid(),
            'title' => $p->getTitle(),
            'updated_at' => $p->getUpdatedAt()?->diffForHumans(),
        ], $userPages),
    ]);
})->middleware('auth');
