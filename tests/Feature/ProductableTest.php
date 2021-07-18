<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\Facades\Cart;
use Ctrlc\Cart\ProductVariant;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductableTest extends TestCase
{
    use RefreshDatabase;

    public Model $productable;

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

    public function test_product_has_variant(): void
    {
        self::assertCount(1, $this->productable->variants);
    }

    public function test_variant_has_productable(): void
    {
        $productVariant = $this->productable->variants->first();
        Cart::add($productVariant, 1);

        self::assertSame($productVariant->productable->toArray(), Cart::items()->first()->item->productable->toArray());
    }
}
