<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Database\Factories;

use Ctrlc\Basket\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class BasketFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName.' basket',
        ];
    }
}
