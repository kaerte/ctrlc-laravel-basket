<?php declare(strict_types=1);
namespace Ctrlc\Basket\Database\Factories;

use Ctrlc\Basket\Models\Basket;
use Illuminate\Database\Eloquent\Factories\Factory;

class BasketFactory extends Factory
{
    protected $model = Basket::class;

    public function definition()
    {
        return [
            'name' => $this->faker->colorName . ' basket',
        ];
    }
}
