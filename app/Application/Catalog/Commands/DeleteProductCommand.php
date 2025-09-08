<?php

namespace Application\Catalog\Commands;

final class DeleteProductCommand
{
    public function __construct(
        public readonly string $productId,
        public readonly ?string $sellerId = null // opcional: validar que el seller borre su producto
    ) {}
}
