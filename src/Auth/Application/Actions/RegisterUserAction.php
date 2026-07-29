<?php

namespace Src\Auth\Application\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Src\Auth\Application\DTOs\RegisterUserDTO;

class RegisterUserAction
{
    public function execute(RegisterUserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        return $user;
    }
}
