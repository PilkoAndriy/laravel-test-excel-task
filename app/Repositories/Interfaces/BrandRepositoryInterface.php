<?php

namespace App\Repositories\Interfaces;

interface BrandRepositoryInterface
{
    public function getAll();

    public function save($data);
}
