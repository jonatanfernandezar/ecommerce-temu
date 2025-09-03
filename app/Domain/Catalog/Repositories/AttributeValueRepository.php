<?php

namespace Domain\Catalog\Repositories;

use Domain\Catalog\Entities\AttributeValue;
use Domain\Catalog\ValueObjects\AttributeValueId;

interface AttributeValueRepository
{
    public function save(AttributeValue $value): void;
    public function findById(AttributeValueId $id): ?AttributeValue;
    /** @return AttributeValue[] */
    public function findAll(): array;
    public function delete(AttributeValueId $id): void;
}
