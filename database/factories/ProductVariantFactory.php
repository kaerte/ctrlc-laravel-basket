<?php declare(strict_types=1);
namespace Ctrlc\Basket\Database\Factories;

use Ctrlc\Basket\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName,
            'price' => random_int(100, 5000),
        ];
    }
}
