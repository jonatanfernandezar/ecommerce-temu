<?php

namespace Application\Catalog\Services;

use Application\Catalog\Commands\CreateProductCommand;
use App\Domain\Catalog\Entities\Product;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Domain\Catalog\Repositories\CategoryRepository as CategoryRepositoryInterface;
use Domain\Catalog\Repositories\BrandRepository as BrandRepositoryInterface;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\BrandId;
use App\Domain\Catalog\ValueObjects\Price;
use Domain\Shared\ValueObjects\Quantity;

final class CreateProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private BrandRepositoryInterface $brandRepository
    ) {}

    public function execute(CreateProductCommand $cmd): Product
    {
        if ($cmd->price <= 0) {
            throw new \InvalidArgumentException("Price must be greater than zero.");
        }

        if (!$this->categoryRepository->findById(new CategoryId($cmd->categoryId))) {
            throw new \InvalidArgumentException("Category does not exist.");
        }

        if ($cmd->brandId && !$this->brandRepository->findById(new BrandId($cmd->brandId))) {
            throw new \InvalidArgumentException("Brand does not exist.");
        }

        $product = new Product(
            ProductId::generate(),
            $cmd->name,
            $cmd->description,
            new Price($cmd->price),
            new Quantity($cmd->stock ?? 0),
            new CategoryId($cmd->categoryId),
            $cmd->brandId ? new BrandId($cmd->brandId) : null,
            $cmd->attributes ?? []
        );

        $this->productRepository->save($product);

        return $product;
    }
}
