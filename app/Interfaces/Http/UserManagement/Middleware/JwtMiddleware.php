<?php

namespace App\Interfaces\Http\UserManagement\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class JwtMiddleware
{
    private string $secret;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET', 'changeme');
    }

    public function handle(Request $request, Closure $next)
    {
        Log::info('JwtMiddleware: inicio del handle');

        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            Log::warning('JwtMiddleware: token no proporcionado');
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            Log::info('JwtMiddleware: token decodificado', (array)$decoded);

            $request->setUserResolver(fn() => (object)[
                'id' => $decoded->sub,
                'role' => $decoded->role ?? 'client'
            ]);

        } catch (\Exception $e) {
            Log::error('JwtMiddleware: token inválido', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid token', 'message' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
