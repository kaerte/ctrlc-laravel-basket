<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName,
            'price' => random_int(100, 5000),
            'quantity' => 1,
        ];
    }
}
