<?php

namespace Application\Seller\Services;

use Application\Seller\Commands\RegisterSellerProfileCommand;
use Application\Seller\DTO\SellerProfileDTO;
use Domain\Seller\Repositories\SellerProfileRepositoryInterface;
use Domain\Seller\Entities\SellerProfile;
use Domain\Seller\ValueObjects\SellerId;

final class RegisterSellerProfileService
{
    public function __construct(
        private SellerProfileRepositoryInterface $repository
    ) {}

    public function execute(RegisterSellerProfileCommand $command): SellerProfileDTO
    {
        $profile = new SellerProfile(
            new SellerId($command->sellerId),
            $command->address,
            $command->phone,
            $command->description
        );

        $this->repository->save($profile);

        return SellerProfileDTO::fromDomain($profile);
    }
}
