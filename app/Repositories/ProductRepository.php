<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get all brands.
     *
     * @return Product $product
     */
    public function getAll()
    {
        return $this->product->get();
    }

    /**
     * Get all brands.
     *
     * @return Product $product
     */
    public function paginate($perPage)
    {
        return $this->product->with(['category.parent','brand'])->paginate($perPage);
    }

    /**
     * Save Product
     *
     * @param $data
     * @return Product $product
     */
    public function save($data)
    {
        return $this->product->create($data);
    }
}
