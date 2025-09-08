<?php

namespace Application\Catalog\Services;

use Application\Catalog\Commands\DeleteProductCommand;
use Domain\Catalog\Repositories\ProductRepositoryInterface;

final class DeleteProductService
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function execute(DeleteProductCommand $cmd): void
    {
        $product = $this->products->findById($cmd->productId);
        if (!$product) {
            throw new \RuntimeException("Product not found: {$cmd->productId}");
        }

        // opcional: validar sellerId coincide con product->sellerId()
        if ($cmd->sellerId !== null && method_exists($product, 'getSellerId')) {
            if ($product->getSellerId() !== $cmd->sellerId) {
                throw new \RuntimeException("Not authorized to delete this product");
            }
        }

        if (method_exists($this->products, 'delete')) {
            $this->products->delete($product);
        } else {
            // si tu repo no implementa delete, marca como inactive o lanza
            throw new \RuntimeException("ProductRepository does not support delete");
        }
    }
}
