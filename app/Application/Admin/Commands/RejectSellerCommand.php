<?php

namespace Application\Admin\Commands;

/**
 * Command used by an admin to reject a seller application.
 *
 * @param string $adminId AdminId value (UUID string)
 * @param string $sellerId SellerId value (UUID string)
 * @param string|null $reason optional rejection reason
 */
final class RejectSellerCommand
{
    public function __construct(
        public readonly string $adminId,
        public readonly string $sellerId,
        public readonly ?string $reason = null
    ) {}
}
