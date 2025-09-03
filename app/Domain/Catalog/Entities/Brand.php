<?php

namespace Domain\Catalog\Entities;

use Domain\Catalog\ValueObjects\BrandId;
use Domain\Catalog\ValueObjects\BrandName;

class Brand
{
    private BrandId $id;
    private BrandName $name;

    public function __construct(BrandId $id, BrandName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): BrandId
    {
        return $this->id;
    }

    public function name(): BrandName
    {
        return $this->name;
    }

    public function rename(BrandName $newName): void
    {
        $this->name = $newName;
        // Aquí podrías disparar un evento: BrandRenamed si es necesario
    }
}
