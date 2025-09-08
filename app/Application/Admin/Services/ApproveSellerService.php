<?php

namespace Application\Admin\Services;

use Application\Admin\Commands\ApproveSellerCommand;
use Application\Admin\DTO\AdminReportDTO;
use Domain\Admin\Repositories\AdminRepositoryInterface;
use Domain\Seller\Repositories\SellerRepositoryInterface;
use Domain\Seller\ValueObjects\SellerId;
use Domain\Admin\ValueObjects\AdminId;
use Domain\Admin\Events\SellerApproved;

/**
 * Service to approve seller applications by an admin.
 */
final class ApproveSellerService
{
    public function __construct(
        private AdminRepositoryInterface $admins,
        private SellerRepositoryInterface $sellers,
        private ?callable $eventDispatcher = null // optional dispatcher: function($event): void
    ) {}

    /**
     * Approve a seller application.
     *
     * @throws \RuntimeException if admin not allowed or seller not found or domain method missing
     */
    public function execute(ApproveSellerCommand $cmd): void
    {
        $adminId = new AdminId($cmd->adminId);
        $admin = $this->admins->findById($adminId);
        if ($admin === null) {
            throw new \RuntimeException("Admin not found: {$cmd->adminId}");
        }

        if (!$admin->canApproveSeller()) {
            throw new \RuntimeException("Admin {$cmd->adminId} does not have permission to approve sellers.");
        }

        $sellerId = new SellerId($cmd->sellerId);
        $seller = $this->sellers->findById($sellerId);
        if ($seller === null) {
            throw new \RuntimeException("Seller not found: {$cmd->sellerId}");
        }

        // Domain operation: prefer seller->approve() in domain.
        if (!method_exists($seller, 'approve')) {
            throw new \RuntimeException('Domain: Seller entity does not expose approve() — implement Seller::approve() to change seller state.');
        }

        $seller->approve();

        // persist change
        $this->sellers->save($seller);

        // dispatch domain event if dispatcher provided
        if ($this->eventDispatcher !== null) {
            ($this->eventDispatcher)(new SellerApproved($sellerId));
        }
    }
}
