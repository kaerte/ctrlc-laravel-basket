<?php declare(strict_types=1);
namespace Ctrlc\Basket\Database\Factories;

use Ctrlc\Basket\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName,
        ];
    }
}
