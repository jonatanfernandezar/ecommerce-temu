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
        $command = new LoginUserCommand(
            email: $request->input('email'),
            password: $request->input('password')
        );

        $token = $this->loginUserService->execute($command);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }
}
