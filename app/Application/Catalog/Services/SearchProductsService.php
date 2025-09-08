<?php

namespace Application\Catalog\Services;

use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Application\Catalog\DTO\ProductDTO;
use App\Domain\Catalog\Entities\Product;

final class SearchProductsService
{
    public function __construct(private ProductRepositoryInterface $products) {}

    /**
     * @param array<string, mixed> $filters example: ['query'=>'phone','category'=>'123']
     * @return ProductDTO[]
     */
    public function execute(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        /** @var Product[] $results */
        $results = $this->products->findAll();

        return array_map(
            fn(Product $p) => ProductDTO::fromDomain($p),
            $results
        );
    }
}
