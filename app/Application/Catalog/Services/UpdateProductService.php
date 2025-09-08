<?php

namespace Application\Catalog\Services;

use Application\Catalog\Commands\UpdateProductCommand;
use Application\Catalog\DTO\ProductDTO;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use App\Domain\Catalog\ValueObjects\Price;
use App\Domain\Catalog\Entities\Product;

final class UpdateProductService
{
    public function __construct(private ProductRepositoryInterface $products) {}

    public function execute(UpdateProductCommand $cmd): ProductDTO
    {
        /** @var Product|null $product */
        $product = $this->products->findById($cmd->productId);
        if (!$product) {
            throw new \RuntimeException("Product not found: {$cmd->productId}");
        }

        if ($cmd->name !== null) {
            $product->reName($cmd->name);
        }

        if ($cmd->description !== null) {
            $product->changeDescription($cmd->description);
        }

        if ($cmd->price !== null) {
            $priceVo = Price::fromFloat($cmd->price, 'USD');
            $product->changePrice($priceVo);
        }

        if ($cmd->stock !== null) {
            $product->resetStock($cmd->stock);
        }

        // TODO: atributos, categoría, marca, etc.

        $this->products->save($product);

        return ProductDTO::fromDomain($product);
    }
}
