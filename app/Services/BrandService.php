<?php


namespace App\Services;

use App\Repositories\Interfaces\BrandRepositoryInterface;

class BrandService
{
    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }
}
