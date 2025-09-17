<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\RegisterUserService;
use Application\UserManagement\Commands\RegisterUserCommand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class RegisterUserController
{
    private RegisterUserService $service;

    public function __construct(RegisterUserService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|min:8',
            'role'     => 'required|string|in:client,seller,admin',
        ]);

        $command = new RegisterUserCommand(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        );

        $userDTO = $this->service->execute($command);

        return response()->json($userDTO->toArray(), 201);
    }
}
