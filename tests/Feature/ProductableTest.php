<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\Models\ProductVariant;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User;
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
                ProductVariant::factory(),
                'variants'
            )
            ->create();
    }

    public function test_product_has_variant()
    {
        self::assertCount(1, $this->productable->variants);
    }
}
