<?php

namespace App\Repositories\Interfaces;

interface BrandRepositoryInterface
{
    public function getAll();

    public function save(array $data);

    public function firstOrCreate(array $attributes = [], array $values = []);
}
