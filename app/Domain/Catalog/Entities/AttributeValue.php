<?php

namespace Domain\Catalog\Entities;

use Domain\Catalog\ValueObjects\AttributeValueId;
use Domain\Catalog\ValueObjects\AttributeValueName;
use Domain\Catalog\ValueObjects\AttributeId;

class AttributeValue
{
    private AttributeValueId $id;
    private AttributeId $attributeId;
    private AttributeValueName $value;

    public function __construct(AttributeValueId $id, AttributeId $attributeId, AttributeValueName $value)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
        $this->value = $value;
    }

    public function id(): AttributeValueId
    {
        return $this->id;
    }

    public function attributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function value(): AttributeValueName
    {
        return $this->value;
    }

    public function rename(AttributeValueName $newValue): void
    {
        $this->value = $newValue;
    }
}
