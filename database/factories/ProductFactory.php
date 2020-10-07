<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'article' => $this->faker->unique()->ean13,
            'description' => $this->faker->text($maxNbChars = 200),
            'price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 10000),
            'guarantee' => $this->faker->randomDigit,
            'in_stock' => $this->faker->boolean($chanceOfGettingTrue = 90) ,
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
