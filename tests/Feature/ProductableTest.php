<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Models\ProductVariant;
use Ctrlc\Basket\Models\ProductVariantOption;
use Ctrlc\Basket\Tests\TestCase;
use Ctrlc\Basket\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductableTest extends TestCase
{
    use RefreshDatabase;

    public User $productable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productable = User::factory()
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
        self::assertCount(1, $this->productable->variants);
    }

    public function test_product_has_variant_options()
    {
        self::assertCount(3, $this->productable->variants->first()->options);
    }

    public function test_option_has_variant()
    {
        self::assertCount(1, $this->productable->variants->first()->options->first()->variants);
    }
}
