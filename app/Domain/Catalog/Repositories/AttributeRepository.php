<?php

namespace Domain\Catalog\Repositories;

use Domain\Catalog\Entities\Attribute;
use Domain\Catalog\ValueObjects\AttributeId;

interface AttributeRepository
{
    public function save(Attribute $attribute): void;
    public function findById(AttributeId $id): ?Attribute;
    /** @return Attribute[] */
    public function findAll(): array;
    public function delete(AttributeId $id): void;
}
