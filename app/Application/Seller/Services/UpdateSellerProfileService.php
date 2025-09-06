<?php

namespace Application\Seller\Services;

use Application\Seller\Commands\UpdateSellerProfileCommand;
use Application\Seller\DTO\SellerProfileDTO;
use Domain\Seller\Repositories\SellerProfileRepositoryInterface;
use Domain\Seller\ValueObjects\SellerId;
use Domain\Seller\Exceptions\SellerDomainException;

final class UpdateSellerProfileService
{
    public function __construct(
        private SellerProfileRepositoryInterface $repository
    ) {}

    public function execute(UpdateSellerProfileCommand $command): SellerProfileDTO
    {
        $sellerId = new SellerId($command->sellerId);
        $profile = $this->repository->findById($sellerId);

        if (!$profile) {
            throw new SellerDomainException("SellerProfile not found: {$command->sellerId}");
        }

        $profile->update(
            $command->address,
            $command->phone,
            $command->description
        );

        $this->repository->save($profile);

        return SellerProfileDTO::fromDomain($profile);
    }
}
