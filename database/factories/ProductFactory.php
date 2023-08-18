<?php

namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $f = $this->faker->randomElement([ 'shoes' , 'shirts' , 'pants', 'shorts', 'watches',
        'bags', 'accessories', 'sport wears', 'jackets', 'hats', 'dress']);
        return [
            'name' => $this->faker->words(2, true), // Generate a random product name
                'description' => $this->faker->paragraph,
                'price' => $this->faker->randomFloat(2, 100, 1000),
                'category' => $f,

                'gender' => $this->faker->randomElement(['men', 'women', 'boys','girls']),
                'brand_name' => $this->faker->company,
                'user_id' => $this->faker->numberBetween(100, 104),
                'material' => $this->faker->word,
                'photo' => 'fake_products/' .$f.$this->faker->numberBetween(1, 2). '.jpg',

                'sizes' => json_encode($this->faker->randomElements(['S', 'M', 'L', 'XL'], $this->faker->numberBetween(1, 3))),
                'quantity' => $this->faker->numberBetween(10, 100),
                'sale' => $this->faker->numberBetween(0, 50),
                'in_stock' => true,
                'average_rating' => $this->faker->randomFloat(2, 2, 5),
            //
        ];
    }
}
