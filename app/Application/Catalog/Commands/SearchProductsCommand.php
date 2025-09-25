<?php

namespace Application\Catalog\Commands;

final class SearchProductsCommand
{
    public function __construct(
        public readonly ?string $keyword = null,
        public readonly ?string $categoryId = null,
        public readonly ?string $brandId = null,
        public readonly ?int $page = 1,
        public readonly ?int $limit = 20
    ) {}
}
