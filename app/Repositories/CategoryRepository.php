<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var Category
     */
    protected $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get all brands.
     *
     * @return Category[]
     */
    public function getAll()
    {
        return $this->category->all();
    }

    /**
     * Save Post
     *
     * @param array $attributes
     * @return Category
     */
    public function save(array $attributes = [])
    {
        return $this->category->create($attributes);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Category
     */
    public function firstOrCreate(array $attributes = [], array $values = [])
    {
        return $this->category->firstOrCreate($attributes, $values);
    }
}
