<?php

namespace Application\Catalog\Services;

use Application\Catalog\Commands\UpdateProductCommand;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Domain\Catalog\Repositories\CategoryRepository as CategoryRepositoryInterface;
use Domain\Catalog\Repositories\BrandRepository as BrandRepositoryInterface;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\BrandId;
use App\Domain\Catalog\ValueObjects\Price;

final class UpdateProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private BrandRepositoryInterface $brandRepository
    ) {}

    public function execute(UpdateProductCommand $cmd): \App\Domain\Catalog\Entities\Product
    {
        $productId = new ProductId($cmd->productId);
        $product   = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \InvalidArgumentException("Producto no encontrado.");
        }

        if ($cmd->name !== null) {
            $product->rename($cmd->name);
        }

        if ($cmd->description !== null) {
            $product->changeDescription($cmd->description);
        }

        if ($cmd->price !== null) {
            if ($cmd->price <= 0) {
                throw new \InvalidArgumentException("El precio debe ser mayor a cero.");
            }
            $product->changePrice(new Price($cmd->price));
        }

        if ($cmd->categoryId !== null) {
            $categoryId = new CategoryId($cmd->categoryId);
            if (!$this->categoryRepository->findById($categoryId)) {
                throw new \InvalidArgumentException("La categoría no existe.");
            }
            $product->changeCategory($categoryId);
        }

        if ($cmd->brandId !== null) {
            $brandId = new BrandId($cmd->brandId);
            if (!$this->brandRepository->findById($brandId)) {
                throw new \InvalidArgumentException("La marca no existe.");
            }
            $product->changeBrand($brandId);
        }

        $this->productRepository->save($product);

        return $product; // ✅ Devolver el producto actualizado
    }
}
