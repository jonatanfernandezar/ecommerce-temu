<?php

namespace App\Interfaces\Http\Catalog\Controllers;

use Illuminate\Http\JsonResponse;
use Application\Catalog\Services\DeleteProductService;
use Application\Catalog\Commands\DeleteProductCommand;

final class DeleteProductController
{
    public function __construct(
        private DeleteProductService $service
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $command = new DeleteProductCommand($id);
        $this->service->execute($command);

        return response()->json([], 204); // 204
    }
}
