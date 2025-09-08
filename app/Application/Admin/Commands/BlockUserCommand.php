<?php

namespace Application\Admin\Commands;

/**
 * Command used by an admin to block a user.
 *
 * @param string $adminId AdminId value (UUID string)
 * @param string $userId UserId value (UUID string)
 * @param string|null $reason optional reason for blocking
 */
final class BlockUserCommand
{
    public function __construct(
        public readonly string $adminId,
        public readonly string $userId,
        public readonly ?string $reason = null
    ) {}
}
