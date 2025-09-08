<?php

namespace Application\Seller\Services;

use Application\Seller\Commands\ManageSellerProductsCommand;
use Application\Seller\DTO\SellerProductDTO;
use App\Domain\Catalog\Entities\Product;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use App\Domain\Catalog\ValueObjects\Price;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Shared\ValueObjects\Quantity;

final class ManageSellerProductsService
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function execute(ManageSellerProductsCommand $cmd): ?SellerProductDTO
    {
        return match ($cmd->action) {
            'create' => $this->create($cmd),
            'update' => $this->update($cmd),
            'delete' => $this->delete($cmd),
            default  => throw new \InvalidArgumentException("Unknown action: {$cmd->action}"),
        };
    }

    private function create(ManageSellerProductsCommand $cmd): SellerProductDTO
    {
        $product = new Product(
            $cmd->productId ? new ProductId($cmd->productId) : ProductId::generate(),
            $cmd->name ?? '',
            $cmd->description ?? '',
            Price::fromFloat($cmd->price ?? 0, 'USD'),
            new Quantity($cmd->stock ?? 0),
            //$cmd->categoryId ? new CategoryId($cmd->categoryId) : null
            new CategoryId($cmd->categoryId)
        );

        $this->products->save($product);

        return SellerProductDTO::fromDomain($product);
    }

    private function update(ManageSellerProductsCommand $cmd): SellerProductDTO
    {
        $product = $this->products->findById($cmd->productId);
        if (!$product) {
            throw new \RuntimeException("Product not found");
        }

        if ($cmd->name) {
            $ref = new \ReflectionProperty($product, 'name');
            $ref->setAccessible(true);
            $ref->setValue($product, $cmd->name);
        }

        if ($cmd->description) {
            $ref = new \ReflectionProperty($product, 'description');
            $ref->setAccessible(true);
            $ref->setValue($product, $cmd->description);
        }

        if ($cmd->price) {
            $product->changePrice(Price::fromFloat($cmd->price, 'USD'));
        }

        if ($cmd->stock !== null) {
            $ref = new \ReflectionProperty($product, 'stock');
            $ref->setAccessible(true);
            $ref->setValue($product, $cmd->stock);
        }

        $this->products->save($product);

        return SellerProductDTO::fromDomain($product);
    }

    private function delete(ManageSellerProductsCommand $cmd): null
    {
        $product = $this->products->findById($cmd->productId);
        if (!$product) {
            throw new \RuntimeException("Product not found");
        }

        // si el repositorio soporta remove:
        // $this->products->remove($product);

        return null;
    }
}
