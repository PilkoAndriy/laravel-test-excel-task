<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getAll();

    public function paginate($perPage = null);

    public function save(array $attributes = []);

    public function updateOrCreate(array $attributes, array $values = []);
}
