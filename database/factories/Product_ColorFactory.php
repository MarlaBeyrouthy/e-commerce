<?php

namespace Database\Factories;
use App\Models\Product_color;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product_color>
 */
class Product_ColorFactory extends Factory
{
    protected $model = Product_color::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'product_Id'=> $this->faker->unique()->numberBetween(1, 30),
        'color_Id'=> $this->faker->numberBetween(1, 8),
            //
        ];
    }

}
