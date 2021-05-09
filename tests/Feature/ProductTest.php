<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Tests\Product;
use Ctrlc\Basket\Tests\ProductVariant;
use Ctrlc\Basket\Tests\ProductVariantOption;
use Ctrlc\Basket\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()
            ->has(
                ProductVariant::factory()
                    ->has(
                        ProductVariantOption::factory()->count(3),
                        'options'
                    ),
                'variants'
            )
            ->create();
    }

    public function test_product_has_variant()
    {
        self::assertCount(1, $this->product->variants);
    }

    public function test_product_has_variant_options()
    {
        self::assertCount(3, $this->product->variants->first()->options);
    }

    public function test_option_has_variant()
    {
        self::assertCount(1, $this->product->variants->first()->options->first()->variants);
    }
}
