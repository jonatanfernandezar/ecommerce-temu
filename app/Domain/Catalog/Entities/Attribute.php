<?php

namespace Domain\Catalog\Entities;

use Domain\Catalog\ValueObjects\AttributeId;
use Domain\Catalog\ValueObjects\AttributeName;

class Attribute
{
    private AttributeId $id;
    private AttributeName $name;

    public function __construct(AttributeId $id, AttributeName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): AttributeId
    {
        return $this->id;
    }

    public function name(): AttributeName
    {
        return $this->name;
    }

    public function rename(AttributeName $newName): void
    {
        $this->name = $newName;
        // Evento AttributeRenamed opcional
    }
}
