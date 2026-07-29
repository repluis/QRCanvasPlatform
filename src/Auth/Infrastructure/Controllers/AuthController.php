<?php

namespace Src\Auth\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Src\Auth\Application\Actions\LoginUserAction;
use Src\Auth\Application\Actions\LogoutUserAction;
use Src\Auth\Application\Actions\RegisterUserAction;
use Src\Auth\Application\DTOs\LoginUserDTO;
use Src\Auth\Application\DTOs\RegisterUserDTO;
use Src\Auth\Domain\Exceptions\InvalidCredentialsException;
use Src\Shared\Infrastructure\Controllers\BaseController;

class AuthController extends BaseController
{
    public function __construct(
        private readonly LoginUserAction $loginUserAction,
        private readonly LogoutUserAction $logoutUserAction,
        private readonly RegisterUserAction $registerUserAction,
    ) {}

    public function create()
    {
        return Inertia::render('Auth/Views/Login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $dto = RegisterUserDTO::fromRequest($request->only(['name', 'email', 'password']));
        $user = $this->registerUserAction->execute($dto);

        Auth::login($user);

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $dto = LoginUserDTO::fromRequest($request->only(['email', 'password']));

        try {
            $this->loginUserAction->execute($dto);
        } catch (InvalidCredentialsException $e) {
            throw ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function destroy(Request $request)
    {
        $this->logoutUserAction->execute();

        return redirect('/login');
    }
}
