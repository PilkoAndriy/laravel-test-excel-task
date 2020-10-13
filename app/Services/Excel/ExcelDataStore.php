<?php


namespace App\Services\Excel;


use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Excel\Interfaces\ExcelDataStoreInterface;

/**
 * Class ExcelDataStore
 * @package App\Services
 */
class ExcelDataStore implements ExcelDataStoreInterface
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
     * @var BrandRepositoryInterface
     */
    private $brandRepository;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param BrandRepositoryInterface $brandRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(BrandRepositoryInterface $brandRepository, CategoryRepositoryInterface $categoryRepository, ProductRepositoryInterface $productRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Store all data
     *
     * @param array $data
     * @return void
     */
    public function storeData(array $data): void
    {
        if (isset($data['brands'])) {
            $this->storeBrand($data['brands']);
        }
        if (isset($data['categories'])) {
            $this->storeCategory($data['categories']);
        }
        if (isset($data['products'])) {
            $this->storeProduct($data['products']);
        }
    }

    /**
     * Store brand
     *
     * @param array $brands
     * @return void
     */
    protected function storeBrand(array $brands): void
    {
        foreach ($brands as $brand) {
            $tempBrand = $this->brandRepository->firstOrCreate(['title' => $brand['title']]);
            $this->brandIdByTitle[$brand['title']] = $tempBrand->id;
        }
    }

    /**
     * Store categories
     *
     * @param array $categories
     * @return void
     */
    protected function storeCategory(array $categories): void
    {
        foreach ($categories as $category) {
            $tempCategory = $this->categoryRepository->firstOrCreate(['title' => $category['title'], 'parent_id' => $this->categoryIdByTitle[$category['parent_title']] ?? null]);
            $this->categoryIdByTitle[$category['title']] = $tempCategory->id;
        }
    }

    /**
     * Store products
     *
     * @param array $products
     * @return void
     */
    protected function storeProduct(array $products): void
    {
        foreach ($products as $product) {
            $product['brand_id'] = $this->brandIdByTitle[$product['brand_title']];
            $product['category_id'] = $this->categoryIdByTitle[$product['category_title']];
            $this->productRepository->updateOrCreate([
                'name' => $product['name'],
                'article' => $product['article'],
                'brand_id' => $product['brand_id'],
                'category_id' => $product['category_id']
            ], [
                'description' => $product['description'],
                'price' => $product['price'],
                'guarantee' => is_int($product['guarantee']) ? $product['guarantee'] : null,
                'in_stock' => empty($product['in_stock']) ? false : true
            ]);
        }
    }
}
