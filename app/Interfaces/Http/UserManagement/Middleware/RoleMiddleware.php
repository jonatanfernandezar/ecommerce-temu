<?php

namespace App\Interfaces\Http\UserManagement\Middleware;

use Closure;
use Illuminate\Http\Request;

final class RoleMiddleware
{
    /**
     * Maneja la verificación de roles en rutas protegidas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Roles permitidos (admin, seller, client, etc.)
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User not authenticated',
            ], 401);
        }

        if (!in_array($user->role, $roles)) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have permission to access this resource',
            ], 403);
        }

        return $next($request);
    }
}
