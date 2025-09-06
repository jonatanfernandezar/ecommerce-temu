<?php

namespace Application\Seller\Services;

use Application\Seller\DTO\SellerProfileDTO;

final class ViewSellerProfileService
{
    public function __construct(
        private \Domain\Seller\Repositories\SellerProfileRepositoryInterface $repository
    ) {}

    public function execute(string $sellerId): SellerProfileDTO
    {
        $profile = $this->repository->findById(new \Domain\Seller\ValueObjects\SellerId($sellerId));

        if (!$profile) {
            throw new \Domain\Seller\Exceptions\SellerDomainException("SellerProfile not found: {$sellerId}");
        }

        return SellerProfileDTO::fromDomain($profile);
    }
}