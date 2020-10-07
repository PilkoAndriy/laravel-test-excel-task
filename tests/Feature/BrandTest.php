<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testDatabaseHasExpectedColumns()
    {
        $this->assertTrue(
            Schema::hasColumns('brands', [
                'id','title'
            ]), 1);
    }

    public function testBrandHasManyProduct()
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id,'brand_id' => $brand->id]);

        // Method 1: A comment exists in a post's comment collections.
        $this->assertTrue($brand->products->contains($product));

        // Method 2: Count that a post comments collection exists.
        $this->assertEquals(1, $brand->products->count());

        // Method 3: Comments are related to posts and is a collection instance.
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $brand->products);
    }
}
