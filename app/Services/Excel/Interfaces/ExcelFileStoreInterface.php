<?php

namespace App\Services\Excel\Interfaces;

interface ExcelFileStoreInterface
{
    public function store($file): string;
}
