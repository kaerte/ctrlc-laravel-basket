<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantOptionFactory extends Factory
{
    protected $model = ProductVariantOption::class;

    public function definition()
    {
        $sizeOptions = [
            [
                'name' => 'Size',
                'value' => 'S',
            ],
            [
                'name' => 'Size',
                'value' => 'M',
            ],
            [
                'name' => 'Size',
                'value' => 'L',
            ],
            [
                'name' => 'Size',
                'value' => 'XL',
            ],
            [
                'name' => 'Size',
                'value' => 'XXL',
            ],
        ];


        return $this->faker->randomElement($sizeOptions);
    }
}
