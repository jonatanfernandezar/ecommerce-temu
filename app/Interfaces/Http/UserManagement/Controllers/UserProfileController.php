<?php

namespace App\Interfaces\Http\UserManagement\Controllers;

use Application\UserManagement\Services\ViewUserProfileService;
use Application\UserManagement\Services\CreateUserProfileService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class UserProfileController
{
    private ViewUserProfileService $viewService;
    private CreateUserProfileService $updateService;

    public function __construct(
        ViewUserProfileService $viewService,
        CreateUserProfileService $updateService
    ) {
        $this->viewService   = $viewService;
        $this->updateService = $updateService;
    }

    public function show(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $profileDTO = $this->viewService->execute($userId);

        return response()->json($profileDTO);
    }

    public function update(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'name'    => 'nullable|string',
            'email'   => 'nullable|email',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string',
        ]);

        $profileDTO = $this->updateService->execute(
            $userId,
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['phone'] ?? null
        );

        return response()->json($profileDTO);
    }
}