<?php

namespace Application\Catalog\Services;

use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Application\Catalog\DTO\ProductDTO;

final class GetAllProductsService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * @return ProductDTO[]
     */
    public function execute(): array
    {
        $products = $this->productRepository->findAll();

        return array_map(
            fn($product) => ProductDTO::fromDomain($product),
            $products
        );
    }
}
