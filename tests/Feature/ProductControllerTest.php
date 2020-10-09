<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\Paginator;
use Tests\TestCase;
use Mockery;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;

    private $productRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->productRepository = Mockery::mock('App\Repositories\Interfaces\ProductRepositoryInterface');
        $this->app->instance('App\Repositories\Interfaces\ProductRepositoryInterface', $this->productRepository);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndex()
    {
        $this->productRepository->shouldReceive('paginate')->once()->andReturn([]);

        $response = $this->get('/products');

        $response->assertSuccessful();
        $response->assertSeeText('Article');
        $response->assertViewHas('products');
    }

    public function testProductDisplay()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->count(10)->create(['category_id' => $category->id,'brand_id' => $brand->id]);
        $paginateProduct = new Paginator(collect($product),6);
        $this->productRepository->shouldReceive('paginate')->once()->andReturn($paginateProduct);

        $response = $this->get('/products');

        $response->assertSuccessful();
        $response->assertSeeText('Article');
        $response->assertViewHas('products',$paginateProduct);


    }
}
