<?php

namespace App\Interfaces\Http\Catalog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Application\Catalog\Services\SearchProductsService;
use Application\Catalog\Commands\SearchProductsCommand;

final class SearchProductsController
{
    public function __construct(
        private SearchProductsService $service
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $command = new SearchProductsCommand(
            keyword: $request->query('q', null),
            categoryId: $request->query('categoryId', null),
            brandId: $request->query('brandId', null),
            page: $request->query('page', 1),
            limit: $request->query('limit', 20)
        );

        $products = $this->service->execute($command);

        return response()->json($products);
    }
}
