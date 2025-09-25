<?php

namespace Application\Catalog\Services;

use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Application\Catalog\DTO\ProductDTO;
use Domain\Catalog\ValueObjects\ProductId;

final class GetProductByIdService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(string $id): ProductDTO
    {
        $productId = new ProductId($id);
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \InvalidArgumentException("Producto con ID {$id} no encontrado.");
        }

        return ProductDTO::fromDomain($product);
    }
}
