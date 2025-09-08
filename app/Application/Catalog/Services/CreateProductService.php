<?php

namespace Application\Catalog\Services;

use Application\Catalog\Commands\CreateProductCommand;
use Application\Catalog\DTO\ProductDTO;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Domain\Catalog\Repositories\CategoryRepository;
use Domain\Catalog\Repositories\BrandRepository;
use App\Domain\Catalog\Entities\Product;
use App\Domain\Catalog\ValueObjects\Price;
use Domain\Shared\ValueObjects\Quantity;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\CategoryId; // 👈 Import correcto
use Domain\Catalog\ValueObjects\BrandId;    // 👈 Import correcto

final class CreateProductService
{
    public function __construct(
        private ProductRepositoryInterface $products,
        private CategoryRepository $categories,
        private BrandRepository $brands
    ) {}

    public function execute(CreateProductCommand $cmd): ProductDTO
    {
        // Validación de Application
        if ($cmd->price < 0) {
            throw new \InvalidArgumentException("Price cannot be negative");
        }

        // Mapear Price VO
        $priceVo = Price::fromFloat($cmd->price, 'USD');

        // Validar existencia de Category
        if ($cmd->categoryId !== null) {
            $cat = $this->categories->findById(new CategoryId($cmd->categoryId));
            if (!$cat) {
                throw new \RuntimeException("Category not found: {$cmd->categoryId}");
            }
        }

        // Validar existencia de Brand
        if ($cmd->brandId !== null) {
            $brand = $this->brands->findById(new BrandId($cmd->brandId));
            if (!$brand) {
                throw new \RuntimeException("Brand not found: {$cmd->brandId}");
            }
        }

        // Crear entidad Product
        $product = new Product(
            $cmd->productId ? new ProductId($cmd->productId) : ProductId::generate(),
            $cmd->name,
            $cmd->description,
            $priceVo,
            new Quantity($cmd->stock),
            new CategoryId($cmd->categoryId),
            $cmd->brandId ? new BrandId($cmd->brandId) : null
        );

        $this->products->save($product);

        return ProductDTO::fromDomain($product);
    }
}
