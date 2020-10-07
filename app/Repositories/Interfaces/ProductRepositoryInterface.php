<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getAll();

    public function paginate($perPage);

    public function save($data);
}
