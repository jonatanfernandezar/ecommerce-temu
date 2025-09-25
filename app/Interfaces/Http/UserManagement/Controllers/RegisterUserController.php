<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\RegisterUserService;
use Application\UserManagement\Commands\RegisterUserCommand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Domain\UserManagement\Exceptions\UserDomainException;

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

        try {
            $command = new RegisterUserCommand(
                $data['name'],
                $data['email'],
                $data['password'],
                $data['role']
            );

            $userDTO = $this->service->execute($command);

            return response()->json($userDTO->toArray(), 201);

        } catch (UserDomainException $e) {
            // Excepción lanzada explícitamente desde la capa de dominio
            return response()->json(['message' => $e->getMessage()], 422);

        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Email already exists'], 422);
            }
            return response()->json(['message' => 'Database error'], 500);
        } catch (\Throwable $e) {
            Log::error("❌ Error inesperado en RegisterUserController", [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Unexpected error'], 500);
        }
    }
}
