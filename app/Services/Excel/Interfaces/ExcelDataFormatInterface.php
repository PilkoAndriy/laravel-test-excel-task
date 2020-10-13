<?php

namespace App\Services\Excel\Interfaces;

interface ExcelDataFormatInterface
{
    public function format(array $excelTableData): void;

    public function fixedBrokenData(): void;

    public function setSessionFailedRowInfo(): void;

    public function getFormattedData(): array;

}
