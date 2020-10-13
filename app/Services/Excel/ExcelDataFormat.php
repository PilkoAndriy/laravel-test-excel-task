<?php


namespace App\Services\Excel;


use App\Services\Excel\Interfaces\ExcelDataFormatInterface;

/**
 * Class ExcelDataFormat
 * @package App\Services
 */
class ExcelDataFormat implements ExcelDataFormatInterface
{
    /**
     * Cell index to real data
     *
     */
    const CELL_INDEX_NAME = [
        'category' => 0,
        'category_child' => 1,
        'category_child_child' => 2,
        'brand' => 3,
        'name' => 4,
        'article' => 5,
        'description' => 6,
        'price' => 7,
        'guarantee' => 8,
        'in_stock' => 9,
    ];

    /**
     * Products array
     *
     * @var array
     */
    protected $products;
    /**
     * Brands array
     *
     * @var array
     */
    protected $brands;
    /**
     * Categories array
     *
     * @var array
     */
    protected $categories;
    /**
     * Failed item array
     *
     * @var array
     */
    protected $failedImportData;

    /**
     * Failed item statistic
     *
     * @var array
     */
    protected $failedItemStatistic;

    /**
     * Format data for saving
     *
     * @param array $excelTableData Data for format from product excel table to laravel model
     * @return void
     */
    public function format(array $excelTableData): void
    {
        foreach ($excelTableData as $excelTableRow) {
            foreach (self::CELL_INDEX_NAME as $cellKey => $cellValue) {
                $row = $this->getRowCellByKey($excelTableRow, $cellKey);
                if ($row) {
                    switch ($cellKey) {
                        case 'category':
                            $this->addCategory($row);
                            break;
                        case 'category_child_child':
                        case 'category_child':
                            $this->addCategory($row, $excelTableRow[$cellValue - 1]);
                            break;
                        case 'brand':
                            $this->addBrand($row);
                            break;
                    }
                } else {
                    $this->passToFailedCollection($excelTableRow, $cellKey);
                    continue 2;
                }
            }
            $this->addProduct($excelTableRow);
        }
    }

    /**
     * Check if cell empty, if empty then rows is broken and add row to failed row data
     *
     * @param $excelTableRow
     * @param $cellKey
     * @return null|mixed
     */
    protected function getRowCellByKey($excelTableRow, $cellKey)
    {
        return $excelTableRow[self::CELL_INDEX_NAME[$cellKey]] ?? NULL;
    }

    /**
     * Add new unique(by array key)  category item
     *
     * @param $title
     * @param null $parentTitle
     * @return void
     */
    protected function addCategory($title, $parentTitle = null): void
    {
        $this->categories[$title] = ['title' => $title, 'parent_title' => $parentTitle];
    }


    /**
     * Add new unique(by array key) brand item
     *
     * @param $title
     * @return void
     */
    protected function addBrand($title): void
    {
        $this->brands[$title] = ['title' => $title];
    }


    /**
     * Added excel row to failed import item
     *
     * @param array $array
     * @param $key
     * @return void
     */
    protected function passToFailedCollection(array $array, $key): void
    {
        $this->failedImportData[] = [
            'broken_key' => $key,
            'row' => $array
        ];
    }

    /**
     * Add new product item
     *
     * @param array $array
     * @return void
     */
    protected function addProduct(array $array): void
    {
        $this->products[] = [
            'name' => $array[self::CELL_INDEX_NAME['name']],
            'article' => $array[self::CELL_INDEX_NAME['article']],
            'description' => $array[self::CELL_INDEX_NAME['description']],
            'price' => $array[self::CELL_INDEX_NAME['price']],
            'guarantee' => $array[self::CELL_INDEX_NAME['guarantee']],
            'in_stock' => $array[self::CELL_INDEX_NAME['in_stock']],
            'brand_title' => $array[self::CELL_INDEX_NAME['brand']],
            'category_title' => $array[self::CELL_INDEX_NAME['category_child_child']]
        ];
    }


    /**
     * Try to fixed broken rows
     *
     * @return void
     */
    public function fixedBrokenData(): void
    {
        $dataForFixed = [];
        foreach ($this->failedImportData as $failedItemKey => $failedItemValue) {
            if ($this->ifFirstRowBroken($failedItemValue)) {
                $dataForFixed[] = $this->getFixedRow($failedItemValue['row']);
                $this->removeFromFailedItemByKey($failedItemKey);
            }
        }
        $this->format($dataForFixed);
    }


    /**
     * Check if first row fixed
     * @param $failedItem
     * @return bool
     */
    protected function ifFirstRowBroken(array $failedItem): bool
    {
        if ($failedItem['broken_key'] == 'category' && count($failedItem['row']) > count(self::CELL_INDEX_NAME)) {
            return true;
        }
        return false;
    }

    /**
     * @param $excelTableRow
     * @return array
     */
    protected function getFixedRow($excelTableRow): array
    {
        array_shift($excelTableRow);
        return $excelTableRow;
    }

    /**
     * @param $key
     * @return void
     */
    protected function removeFromFailedItemByKey($key): void
    {
        unset($this->failedImportData[$key]);
    }

    /**
     * Put to session info about import
     *
     * @return void
     */
    public function setSessionFailedRowInfo(): void
    {
        $info = [];
        foreach (self::CELL_INDEX_NAME as $key => $value) {
            $info[$key] = 0;
        }
        foreach ($this->failedImportData as $item) {
            $info[$item['broken_key']]++;
        }
        session()->put('excel.export.error', $info);
    }

    /**
     * Get formatted data
     *
     * @return array
     */
    public function getFormattedData(): array
    {
        return [
            'brands' => $this->brands,
            'categories' => $this->categories,
            'products' => $this->products,
        ];
    }
}
