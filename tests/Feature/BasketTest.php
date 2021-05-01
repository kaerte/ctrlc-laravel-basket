<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Facades\Basket;
use Ctrlc\Basket\Tests\Product;
use Ctrlc\Basket\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasketTest extends TestCase
{
    use RefreshDatabase;

    public Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()
            ->hasVariants(1, [
                'default' => 1,
            ])
            ->create();
    }

    public function test_product_creation(): void
    {
        $variant = $this->product->variant;
        $this->assertDatabaseHas('product_variants', [
            'name' => $variant->name,
            'default' => 1,
        ]);

        self::assertInstanceOf(Product::class, $variant->item);
    }

    public function test_add_to_basket_total(): void
    {
        $variant = $this->product->variant;
        Basket::add($variant)
            ->add($variant);

        self::assertEquals($variant->price * 2, Basket::total());
    }

    public function test_add_to_basket_quantity(): void
    {
        $variant = $this->product->variant;
        $basket = Basket::add($variant)
            ->add($variant);

        self::assertEquals(2, $basket->items->first()->quantity);
    }

    public function test_remove_from_basket(): void
    {
        $variant = $this->product->variant;
        $basket = Basket::add($variant)
            ->add($variant)
            ->remove($variant);

        self::assertEquals(1, $basket->items->first()->quantity);
    }

    public function test_remove_all_from_basket(): void
    {
        $variant = $this->product->variant;
        $basket = Basket::add($variant)
            ->add($variant)
            ->remove($variant, 2);

        self::assertEmpty($basket->items);
        self::assertEquals(0, Basket::total());
    }
}
