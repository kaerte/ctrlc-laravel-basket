<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Database\Factories;

use Ctrlc\Cart\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName.' basket',
        ];
    }
}
