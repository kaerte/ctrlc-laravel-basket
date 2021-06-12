<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Carbon\Carbon;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->productable = User::factory()
            ->hasVariants(1, [
                'default' => 1,
            ])
            ->create();

        $this->productVariant = $this->productable->defaultVariant;
    }

    public function test_product_creation(): void
    {
        self::assertInstanceOf(User::class, $this->productVariant->productable);
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

        self::assertEquals(2, $basket->contents->first()->quantity);
    }

    public function test_remove_from_basket(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant);

        self::assertEquals(1, $basket->contents->first()->quantity);
    }

    public function test_remove_all_from_basket(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant, 2);

        self::assertEmpty($basket->contents);
        self::assertEquals(0, Basket::total());
    }

    public function test_locked(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant, 2)
            ->lock();

        self::assertTrue($basket->locked);
        self::assertInstanceOf(Carbon::class, $basket->locked_at);
    }

    public function test_unlocked(): void
    {
        $basket = Basket::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant, 2)
            ->lock()
            ->unlock();

        self::assertFalse($basket->locked);
        self::assertNull($basket->locked_at);
    }
}
