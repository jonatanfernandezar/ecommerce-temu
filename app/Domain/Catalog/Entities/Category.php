<?php

namespace Domain\Catalog\Entities;

use Domain\Catalog\ValueObjects\CategoryId;
use Domain\Catalog\ValueObjects\CategoryName;
use Domain\Catalog\ValueObjects\CategorySlug;

class Category
{
    private CategoryId $id;
    private CategoryName $name;
    private CategorySlug $slug;
    private ?CategoryId $parentId;

    public function __construct(
        CategoryId $id,
        CategoryName $name,
        CategorySlug $slug,
        ?CategoryId $parentId = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->parentId = $parentId;
    }

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function slug(): CategorySlug
    {
        return $this->slug;
    }

    public function parentId(): ?CategoryId
    {
        return $this->parentId;
    }

    public function rename(CategoryName $newName): void
    {
        $this->name = $newName;
        // Aquí luego podrías disparar un evento: CategoryRenamed
    }

    public function changeSlug(CategorySlug $newSlug): void
    {
        $this->slug = $newSlug;
        // Aquí luego podrías disparar un evento: CategorySlugChanged
    }

    public function assignParent(CategoryId $parentId): void
    {
        $this->parentId = $parentId;
        // Aquí luego podrías disparar un evento: CategoryParentAssigned
    }
}
