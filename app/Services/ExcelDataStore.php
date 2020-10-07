<?php


namespace App\Services;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

/**
 * Class ExcelDataStore
 * @package App\Services
 */
class ExcelDataStore
{
    /**
     * For transform from string title to saved integer ID (In DB)
     *
     * @var array
     */
    protected $brandIdByTitle;

    /**
     * For transform from string title to saved integer ID (In DB)
     *
     * @var
     */
    protected $categoryIdByTitle;

    /**
     * Store all data
     *
     * @param array $data
     */
    public function storeData(array $data)
    {
        if(isset($data['brands'])){
            $this->storeBrand($data['brands']);
        }
        if(isset($data['categories'])){
            $this->storeCategory($data['categories']);
        }
        if(isset($data['products'])){
            $this->storeProduct($data['products']);
        }
    }

    /**
     * Store brand
     *
     * @param array $brands
     */
    protected function storeBrand(array $brands)
    {
        foreach ($brands as $brand){
            $tempBrand = Brand::firstOrCreate(['title' => $brand['title']]);
            $this->brandIdByTitle[$brand['title']] = $tempBrand->id;
        }
    }

    /**
     * Store categories
     *
     * @param array $categories
     */
    protected function storeCategory(array $categories)
    {
        foreach ($categories as $category){
            $tempCategory = Category::firstOrCreate(['title' => $category['title'], 'parent_id' => $this->categoryIdByTitle[$category['parent_title']]??null]);
            $this->categoryIdByTitle[$category['title']] = $tempCategory->id;
        }
    }

    /**
     * Store products
     *
     * @param array $products
     */
    protected function storeProduct(array $products)
    {
        foreach ($products as $product){
            $product['brand_id'] = $this->brandIdByTitle[$product['brand_title']];
            $product['category_id'] = $this->categoryIdByTitle[$product['category_title']];
            Product::updateOrCreate([
                'name' => $product['name'],
                'article' => $product['article'],
                'brand_id' => $product['brand_id'],
                'category_id' => $product['category_id']
            ],[
                'description' => $product['description'],
                'price' => $product['price'],
                'guarantee' => is_int($product['guarantee'])?$product['guarantee']:null,
                'in_stock' => empty($product['in_stock'])?false:true
            ]);
        }
    }
}
