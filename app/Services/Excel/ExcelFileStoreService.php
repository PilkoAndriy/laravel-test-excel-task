<?php

namespace App\Services\Excel;

use App\Services\Excel\Interfaces\ExcelFileStoreInterface;

class ExcelFileStoreService implements ExcelFileStoreInterface
{
    /**
     * Store file
     *
     * @param $file
     * @return string File name with path
     */
    public function store($file): string
    {
        return $file->storeAs('files', $this->generateFileName($file));
    }

    /**
     * Generate unique filename
     *
     * @param $file
     * @return string Generated file name
     */
    protected function generateFileName($file): string
    {
        $uFileName = md5($file->getClientOriginalName() . time());
        return "{$uFileName}.{$file->getClientOriginalExtension()}";
    }
}
