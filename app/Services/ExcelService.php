<?php

namespace App\Services;

class ExcelService
{
    /**
     * Store file
     *
     * @param $file
     * @return string File name with path
     */
    public function store($file)
    {
        return $file->storeAs('files', $this->generateFileName($file));
    }

    /**
     * Generate unique filename
     *
     * @param $file
     * @return string Generated file name
     */
    protected function generateFileName($file)
    {
        $uFileName = md5($file->getClientOriginalName() . time());
        return "{$uFileName}.{$file->getClientOriginalExtension()}";
    }
}
