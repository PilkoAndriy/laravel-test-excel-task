<?php

namespace App\Imports;

use App\Services\ExcelDataFormat;
use App\Services\ExcelDataStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements WithChunkReading, ShouldQueue, WithStartRow, ToArray, WithBatchInserts
{
    /**
     * ProductImport constructor.
     * @param ExcelDataFormat $excelDataFormat
     * @param ExcelDataStore $dataStore
     */
    public function __construct(ExcelDataFormat $excelDataFormat, ExcelDataStore $dataStore)
    {
        $this->excelDataFormat = $excelDataFormat;
        $this->dataStore = $dataStore;
    }

    /**
     * @param array $arrays
     */
    public function array(array $arrays)
    {
        $this->excelDataFormat->format($arrays);
        $this->excelDataFormat->fixedBroken();
        $this->excelDataFormat->setSessionFailedRowInfo();
        $this->dataStore->storeData($this->excelDataFormat->getFormatedData());
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 5000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 5000;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
