<?php declare(strict_types=1);
namespace Ctrlc\Basket\Database\Factories;

use Ctrlc\Basket\Models\BasketItemContract;
use Illuminate\Database\Eloquent\Factories\Factory;

class BasketItemFactory extends Factory
{
    protected $model = BasketItemContract::class;

    public function definition()
    {
        return [
            'currency' => 'GBP',
        ];
    }
}
