<?php


namespace App\Services;


/**
 * Class ExcelDataFormat
 * @package App\Services
 */
class ExcelDataFormat
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
     * @param array $arrays
     */
    public function format(array $arrays)
    {
        foreach ($arrays as $array) {
            foreach (self::CELL_INDEX_NAME as $cellKey => $cellValue) {
                $item = $this->checkStatement($array, $cellKey);
                if ($item) {
                    switch ($cellKey) {
                        case 'category':
                            $this->addCategory($item);
                            break;
                        case 'category_child':
                            $this->addCategory($item, $array[$cellValue - 1]);
                            break;
                        case 'category_child_child':
                            $this->addCategory($item, $array[$cellValue - 1]);
                            break;
                        case 'brand':
                            $this->addBrand($item);
                            break;
                    }
                } else {
                    continue 2;
                }
            }
            $this->addProduct($array);
        }
    }

    /**
     * Try to fixed broken rows
     */
    public function fixedBroken()
    {
        $dataForFixed = [];
        foreach ($this->failedImportData as $failedItemKey => $failedItemValue){
            if($failedItemValue['broken_key'] == 'category' && count($failedItemValue['row']) > count(self::CELL_INDEX_NAME)){
                $dataForFixed[] = $this->fixFirstRowBroken($failedItemValue['row']);
                unset($this->failedImportData[$failedItemKey]);
            }
        }
        $this->format($dataForFixed);
    }


    /**
     * First row fixed
     *
     * @param array $array
     * @return array
     */
    protected function fixFirstRowBroken(array $array)
    {
        array_shift($array);
        return $array;
    }

    /**
     * Put to session info about import
     */
    public function setSessionFailedRowInfo()
    {
        $info = [];
        foreach (self::CELL_INDEX_NAME as $key => $value){
            $info[$key] = 0;
        }
        foreach($this->failedImportData as $item){
            $info[$item['broken_key']]++;
        }
        session()->put('excel.export.error',$info);
    }

    /**
     * Get formated data
     *
     * @return array
     */
    public function getFormatedData()
    {
        return [
            'brands' => $this->brands,
            'categories' => $this->categories,
            'products' => $this->products,
        ];
    }

    /**
     * Check if cell empty, if empty then rows is broken and add row to failed row data
     *
     * @param $array
     * @param $value
     * @return false|mixed
     */
    protected function checkStatement($array, $key)
    {
        if ($array[self::CELL_INDEX_NAME[$key]]) {
            return $array[self::CELL_INDEX_NAME[$key]];
        } else {
            $this->passToFailedCollection($array, $key);
            return false;
        }
    }

    /**
     * Added excel row to failed import item
     *
     * @param array $array
     */
    protected function passToFailedCollection(array $array, $key)
    {
        $this->failedImportData[] = [
            'broken_key' => $key,
            'row' => $array
        ];
    }

    /**
     * Add new unique(by array key)  category item
     *
     * @param $title
     * @param null $parentTitle
     */
    protected function addCategory($title, $parentTitle = null)
    {
        $this->categories[$title] = ['title' => $title, 'parent_title' => $parentTitle];
    }


    /**
     * Add new unique(by array key) brand item
     *
     * @param $title
     */
    protected function addBrand($title)
    {
        $this->brands[$title] = ['title' => $title];
    }


    /**
     * Add new product item
     *
     * @param array $array
     */
    protected function addProduct(array $array)
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
}
