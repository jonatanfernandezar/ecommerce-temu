<?php

namespace Application\Admin\Commands;

/**
 * Command used by an admin to approve a seller.
 *
 * @param string $adminId AdminId value (UUID string)
 * @param string $sellerId SellerId value (UUID string)
 */
final class ApproveSellerCommand
{
    public function __construct(
        public readonly string $adminId,
        public readonly string $sellerId
    ) {}
}
