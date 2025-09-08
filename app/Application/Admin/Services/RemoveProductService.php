<?php

namespace Application\Admin\Services;

use Domain\Admin\Repositories\AdminRepositoryInterface;
use Domain\Catalog\Repositories\ProductRepositoryInterface;
use Domain\Catalog\ValueObjects\ProductId;
use Domain\Admin\ValueObjects\AdminId;
use Domain\Admin\Events\ProductRemoved;
use Application\Admin\Commands\RejectSellerCommand; // only for structure; NOT used here

/**
 * Service to remove a product from catalogue (admin action).
 *
 * NOTE: Your Product entity should expose a domain operation to mark it removed
 * (for example Product::remove() or Product::markAsDeleted()). If not present
 * this service will throw a clear exception asking you to implement it in domain.
 */
final class RemoveProductService
{
    public function __construct(
        private AdminRepositoryInterface $admins,
        private ProductRepositoryInterface $products,
        private ?callable $eventDispatcher = null
    ) {}

    /**
     * Remove a product (domain operation).
     *
     * @param string $adminId AdminId value
     * @param string $productId ProductId value
     * @throws \RuntimeException when domain operation required is missing
     */
    public function execute(string $adminId, string $productId): void
    {
        $aId = new AdminId($adminId);
        $admin = $this->admins->findById($aId);
        if ($admin === null) {
            throw new \RuntimeException("Admin not found: {$adminId}");
        }

        if (!$admin->canRemoveProduct()) {
            throw new \RuntimeException("Admin {$adminId} does not have permission to remove products.");
        }

        $pId = new ProductId($productId);
        $product = $this->products->findById($pId);
        if ($product === null) {
            throw new \RuntimeException("Product not found: {$productId}");
        }

        // Prefer domain method Product::remove() or Product::markAsDeleted()
        if (!method_exists($product, 'remove') && !method_exists($product, 'markAsDeleted')) {
            throw new \RuntimeException('Domain: Product entity must expose remove() or markAsDeleted() to support admin removal.');
        }

        if (!method_exists($product, 'remove')) {
            throw new \RuntimeException('Domain: Product entity must expose remove() to support admin removal.');
        }

        $product->remove();

        $this->products->save($product);

        if ($this->eventDispatcher !== null) {
            ($this->eventDispatcher)(new ProductRemoved($pId));
        }
    }
}
