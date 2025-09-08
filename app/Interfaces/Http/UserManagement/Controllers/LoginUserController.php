<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\LoginUserService;
use Application\UserManagement\Commands\LoginUserCommand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class LoginUserController
{
    private LoginUserService $service;

    public function __construct(LoginUserService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $command = new LoginUserCommand(
            $data['email'],
            $data['password']
        );

        $token = $this->service->execute($command);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer'
        ]);
    }
}