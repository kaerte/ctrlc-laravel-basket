<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Contracts\ProductVariantContract;
use Ctrlc\Basket\Facades\Basket;
use Ctrlc\Basket\Tests\TestCase;
use Ctrlc\Basket\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasketTest extends TestCase
{
    use RefreshDatabase;

    public User $productable;

    public ProductVariantContract $productVariant;

    private int $variantQuantity = 10;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productable = User::factory()
            ->hasVariants(1, [
                'default' => 1,
                'quantity' => $this->variantQuantity,
            ])
            ->create();

        $this->productVariant = $this->productable->defaultVariant;
    }

    public function test_product_creation(): void
    {
        self::assertInstanceOf(User::class, $this->productVariant->productable);
    }

    public function test_add_to_basket(): void
    {
        Basket::add($this->productVariant);
        self::assertEquals(1, Basket::items()->count());
    }

    public function test_add_to_basket_total(): void
    {
        Basket::add($this->productVariant)
            ->add($this->productVariant);

        self::assertEquals($this->productVariant->price * 2, Basket::total());
    }

    public function test_add_to_basket_quantity(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant);

        self::assertEquals(2, $basket->items->first()->quantity);
    }

    public function test_remove_from_basket(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant);

        self::assertEquals(1, $basket->items->first()->quantity);
    }

    public function test_remove_all_from_basket(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant, 2);

        self::assertEmpty($basket->items);
        self::assertEquals(0, Basket::total());
    }

    public function test_add_over_quantity_to_basket(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Basket::add($this->productVariant, 11);
    }

    public function test_add_over_quantity_in_multiple_operations_to_basket(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Basket::add($this->productVariant, 1)
            ->add($this->productVariant, 1)
            ->remove($this->productVariant, 1)
            ->add($this->productVariant, $this->variantQuantity + 1);
    }

    public function test_add_unlimited_quantity_to_basket(): void
    {
        $this->productable = User::factory()
            ->hasVariants(1, [
                'default' => 1,
                'quantity' => null,
            ])
            ->create();

        $this->productVariant = $this->productable->defaultVariant;

        Basket::add($this->productVariant, 1)
            ->add($this->productVariant, 1);

        self::assertEquals(2, Basket::items()->first()->quantity);
    }
}
