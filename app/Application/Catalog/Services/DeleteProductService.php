<?php

namespace Application\Catalog\Services;

use Application\Catalog\Commands\DeleteProductCommand;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Domain\Catalog\ValueObjects\ProductId;

final class DeleteProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(DeleteProductCommand $cmd): void
    {
        // Convertimos el string en Value Object
        $productId = new ProductId($cmd->productId);

        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \InvalidArgumentException("Producto no encontrado.");
        }

        // Soft delete (cambiar status a 'removed')
        $product->remove(); // 👈 corregido

        $this->productRepository->save($product);
    }
}
