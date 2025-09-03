<?php

namespace Domain\Catalog\Repositories;

use Domain\Catalog\Entities\Category;
use Domain\Catalog\ValueObjects\CategoryId;

interface CategoryRepository
{
    /**
     * Persiste una categoría
     */
    public function save(Category $category): void;

    /**
     * Busca una categoría por su ID
     */
    public function findById(CategoryId $id): ?Category;

    /**
     * Obtiene todas las categorías
     * 
     * @return Category[]
     */
    public function findAll(): array;

    /**
     * Elimina una categoría por su ID
     */
    public function delete(CategoryId $id): void;
}
