<?php

namespace Application\Admin\Services;

use Application\Admin\Commands\RejectSellerCommand;
use Domain\Admin\Repositories\AdminRepositoryInterface;
use Domain\Seller\Repositories\SellerRepositoryInterface;
use Domain\Seller\ValueObjects\SellerId;
use Domain\Admin\ValueObjects\AdminId;
use Domain\Admin\Events\SellerRejected;

/**
 * Service to reject seller applications.
 */
final class RejectSellerService
{
    public function __construct(
        private AdminRepositoryInterface $admins,
        private SellerRepositoryInterface $sellers,
        private ?callable $eventDispatcher = null
    ) {}

    public function execute(RejectSellerCommand $cmd): void
    {
        $adminId = new AdminId($cmd->adminId);
        $admin = $this->admins->findById($adminId);
        if ($admin === null) {
            throw new \RuntimeException("Admin not found: {$cmd->adminId}");
        }

        if (!$admin->canApproveSeller()) {
            throw new \RuntimeException("Admin {$cmd->adminId} does not have permission to reject sellers.");
        }

        $sellerId = new SellerId($cmd->sellerId);
        $seller = $this->sellers->findById($sellerId);
        if ($seller === null) {
            throw new \RuntimeException("Seller not found: {$cmd->sellerId}");
        }

        if (!method_exists($seller, 'reject')) {
            throw new \RuntimeException('Domain: Seller entity does not expose reject() — implement Seller::reject() to change seller state.');
        }

        $seller->reject($cmd->reason ?? null);

        $this->sellers->save($seller);

        if ($this->eventDispatcher !== null) {
            ($this->eventDispatcher)(new SellerRejected($sellerId));
        }
    }
}
