<?php

namespace App\Imports;

use App\Services\Excel\ExcelDataFormat;
use App\Services\Excel\ExcelDataStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements WithChunkReading, ShouldQueue, WithBatchInserts, WithStartRow, ToArray
{
    /**
     * @var ExcelDataFormat
     */
    protected $excelDataFormat;
    /**
     * @var ExcelDataStore
     */
    protected $dataStore;

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
        $this->excelDataFormat->fixedBrokenData();
        $this->excelDataFormat->setSessionFailedRowInfo();
        $this->dataStore->storeData($this->excelDataFormat->getFormattedData());
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 2000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 2000;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
