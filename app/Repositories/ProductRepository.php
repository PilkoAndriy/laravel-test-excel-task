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
     * @return Product[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->product->all();
    }

    /**
     * Get all brands.
     *
     * @param null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null)
    {
        return $this->product->with(['category.parent','brand'])->paginate($perPage);
    }

    /**
     * Save Product
     *
     * @param array $attributes
     * @return Product
     */
    public function save(array $attributes = [])
    {
        return $this->product->create($attributes);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Product
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->product->updateOrCreate($attributes,$values);
    }


}
