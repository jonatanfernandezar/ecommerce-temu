<?php

namespace Application\Admin\Services;

use Application\Admin\Commands\BlockUserCommand;
use Domain\Admin\Repositories\AdminRepositoryInterface;
use Domain\UserManagement\Repositories\UserRepositoryInterface;
use Domain\UserManagement\ValueObjects\UserId;
use Domain\Admin\ValueObjects\AdminId;
use Domain\Admin\Events\UserBlocked;

/**
 * Service to block a user by an admin.
 */
final class BlockUserService
{
    public function __construct(
        private AdminRepositoryInterface $admins,
        private UserRepositoryInterface $users,
        private ?callable $eventDispatcher = null
    ) {}

    public function execute(BlockUserCommand $cmd): void
    {
        $adminId = new AdminId($cmd->adminId);
        $admin = $this->admins->findById($adminId);
        if ($admin === null) {
            throw new \RuntimeException("Admin not found: {$cmd->adminId}");
        }

        if (!$admin->canBlockUser()) {
            throw new \RuntimeException("Admin {$cmd->adminId} does not have permission to block users.");
        }

        $userId = new UserId($cmd->userId);
        $user = $this->users->findById($userId);
        if ($user === null) {
            throw new \RuntimeException("User not found: {$cmd->userId}");
        }

        if (!method_exists($user, 'block')) {
            throw new \RuntimeException('Domain: User entity does not expose block() — implement User::block() to change user state.');
        }

        $user->block($cmd->reason ?? null);

        $this->users->save($user);

        if ($this->eventDispatcher !== null) {
            ($this->eventDispatcher)(new UserBlocked($userId));
        }
    }
}
