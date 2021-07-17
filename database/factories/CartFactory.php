<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Database\Factories;

use Ctrlc\Cart\EloquentCart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = EloquentCart::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName.' cart',
        ];
    }
}
