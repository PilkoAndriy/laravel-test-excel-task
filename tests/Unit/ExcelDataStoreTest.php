<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\Excel\ExcelDataStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExcelDataStoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $exelDataStore;

    protected function setUp(): void
    {
        parent::setUp();

        $brandRepository = $this->getMockBuilder('App\Repositories\Interfaces\BrandRepositoryInterface')
            //->setMethods(['...']) // array of methods to set initially or they return null
            ->disableOriginalConstructor() //disable __construct
            ->getMock();

        $categoryRepository = $this->getMockBuilder('App\Repositories\Interfaces\CategoryRepositoryInterface')
            //->setMethods(['...']) // array of methods to set initially or they return null
            ->disableOriginalConstructor() //disable __construct
            ->getMock();

        $productRepository = $this->getMockBuilder('App\Repositories\Interfaces\ProductRepositoryInterface')
            //->setMethods(['...']) // array of methods to set initially or they return null
            ->disableOriginalConstructor() //disable __construct
            ->getMock();

        $this->exelDataStore = new ExcelDataStore($brandRepository, $categoryRepository, $productRepository);
    }

    public function testStoreData()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->first()->id, 'brand_id' => $brand->first()->id]);
        $fakeProduct = $product->attributesToArray();
        $fakeProduct['brand_title'] = $brand->title;
        $fakeProduct['category_title'] = $category->title;

        $this->exelDataStore->storeData([
            'brand' => $brand->attributesToArray(),
            'category' => $category->attributesToArray(),
            'product' => $fakeProduct
        ]);

        $this->assertDatabaseHas('brands', $brand->attributesToArray());
        $this->assertDatabaseHas('categories', $category->attributesToArray());
        $this->assertDatabaseHas('products', $product->attributesToArray());
        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseCount('products', 1);
    }
}
