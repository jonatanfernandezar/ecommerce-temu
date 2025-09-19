<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\RegisterUserService;
use Application\UserManagement\Commands\RegisterUserCommand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class RegisterUserController
{
    private RegisterUserService $service;

    public function __construct(RegisterUserService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): JsonResponse
    {
        Log::info("📥 Llega petición RegisterUserController", $request->all());
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|min:8',
            'role'     => 'required|string|in:client,seller,admin',
        ]);

        Log::info("✅ Datos validados", $data);

        $command = new RegisterUserCommand(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        );
        Log::info("📦 Command creado", (array) $command);
        $userDTO = $this->service->execute($command);
        Log::info("📤 Respuesta desde Service (UserDTO)", $userDTO->toArray());

        return response()->json($userDTO->toArray(), 201);
    }
}
