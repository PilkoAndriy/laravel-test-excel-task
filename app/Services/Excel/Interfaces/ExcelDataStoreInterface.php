<?php

namespace App\Services\Excel\Interfaces;

interface ExcelDataStoreInterface
{
    public function storeData(array $array): void;
}
