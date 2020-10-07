<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;

class BrandRepository implements BrandRepositoryInterface
{
    /**
     * @var Brand
     */
    protected $brand;

    /**
     * BrandRepository constructor.
     * @param Brand $brand
     */
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Get all brands.
     *
     * @return Brand $brand
     */
    public function getAll()
    {
        return $this->brand->get();
    }

    /**
     * Save Post
     *
     * @param $data
     * @return Brand $brand
     */
    public function save($data)
    {
        return $this->brand->create($data);
    }
}
