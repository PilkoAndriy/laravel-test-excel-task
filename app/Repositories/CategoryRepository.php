<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get all brands.
     *
     * @return Category $brand
     */
    public function getAll()
    {
        return $this->category->get();
    }

    /**
     * Save Post
     *
     * @param $data
     * @return Category
     */
    public function save($data)
    {
        return $this->category->create($data);
    }
}