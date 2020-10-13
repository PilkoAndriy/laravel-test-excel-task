<?php

namespace App\Imports;

use App\Services\Excel\ExcelDataFormat;
use App\Services\Excel\ExcelDataStore;
use App\Services\Excel\Interfaces\ExcelDataFormatInterface;
use App\Services\Excel\Interfaces\ExcelDataStoreInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements WithChunkReading, ShouldQueue, WithBatchInserts, WithStartRow, ToArray
{

    const BATCH_SIZE = 500;
    const CHUNK_SIZE = 500;
    const START_ROW = 2;

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
     * @param ExcelDataFormatInterface $excelDataFormat
     * @param ExcelDataStoreInterface $dataStore
     */
    public function __construct(ExcelDataFormatInterface $excelDataFormat, ExcelDataStoreInterface $dataStore)
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
        return self::BATCH_SIZE;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return self::CHUNK_SIZE;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return self::START_ROW;
    }
}
