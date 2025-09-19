<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Illuminate\Http\Request;
use Application\UserManagement\Commands\LoginUserCommand;
use Application\UserManagement\Services\LoginUserService;

class LoginUserController
{
    public function __construct(private LoginUserService $loginUserService) {}

    public function __invoke(Request $request)
    {
        $loginData = $this->loginUserService->execute(
            new LoginUserCommand(
                email: $request->input('email'),
                password: $request->input('password')
            )
        );

        return response()->json($loginData);
    }
}
