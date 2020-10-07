<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('products', [
                'id','name','article','description','price','guarantee','in_stock','brand_id','category_id','deleted_at','created_at','updated_at'
            ]), 1);
    }

    public function testCategoryHasManyProduct()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id,'brand_id' => $brand->id]);

        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertInstanceOf(Category::class, $product->category);

    }
}
