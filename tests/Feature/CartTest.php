<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\CartItemable;
use Ctrlc\Cart\Facades\Cart;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User as Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public Model $productable;

    public CartItemable $productVariant;

    private int $variantAvailableQuantity = 10;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productable = Product::factory()
            ->hasVariants(1, [
                'default' => 1,
                'quantity' => $this->variantAvailableQuantity,
            ])
            ->create();

        $this->productVariant = $this->productable->defaultVariant;
    }

    public function test_add_to_cart(): void
    {
        Cart::add($this->productVariant);
        self::assertEquals(1, Cart::items()->count());
    }

    public function test_add_to_cart_total(): void
    {
        Cart::add($this->productVariant)
            ->add($this->productVariant);

        self::assertEquals($this->productVariant->price * 2, Cart::total());
    }

    public function test_add_to_cart_twice_cart_quantity(): void
    {
        $cart = Cart::add($this->productVariant)
            ->add($this->productVariant);

        self::assertEquals(2, $cart->items->first()->quantity);
    }

    public function test_remove_from_cart(): void
    {
        $cart = Cart::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant);

        self::assertEquals(1, $cart->items->first()->quantity);
    }

    public function test_remove_all_from_cart(): void
    {
        $cart = Cart::add($this->productVariant)
            ->add($this->productVariant)
            ->remove($this->productVariant, 2);

        self::assertEmpty($cart->items);
        self::assertEquals(0, Cart::total());
    }

    public function test_add_over_quantity_to_cart(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Cart::add($this->productVariant, 11);
    }

    public function test_add_over_quantity_in_multiple_operations_to_cart(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Cart::add($this->productVariant, 1)
            ->add($this->productVariant, 1)
            ->remove($this->productVariant, 1)
            ->add($this->productVariant, $this->variantAvailableQuantity + 1);
    }

    public function test_add_unlimited_quantity_to_cart(): void
    {
        $this->productable = Product::factory()
            ->hasVariants(1, [
                'default' => 1,
                'quantity' => null,
            ])
            ->create();

        $this->productVariant = $this->productable->defaultVariant;

        Cart::add($this->productVariant, 1)
            ->add($this->productVariant, 1);

        self::assertEquals(2, Cart::items()->first()->quantity);
    }

    public function test_getting_the_same_basket(): void
    {
        $cart = Cart::add($this->productVariant);
        $secondCart = Cart::get();

        self::assertEquals($cart->id, $secondCart->id);
    }

    public function test_adding_with_invalid_meta(): void
    {
        $this->expectException(\TypeError::class);
        $cart = Cart::add($this->productVariant, 1, true);

        self::assertEquals($cart, $cart);
    }

    public function test_adding_with_meta(): void
    {
        $meta = ['key' => 'value'];
        $cart = Cart::add($this->productVariant, 1, $meta);

        $itemMeta = $cart->items->first()->meta;
        
        self::assertSame($meta, [$itemMeta->first()->key => $itemMeta->first()->value]);
    }

    public function test_update_product_quantity(): void
    {
        Cart::add($this->productVariant, 8)
            ->updateQuantity($this->productVariant, 5);

        self::assertEquals(5, Cart::items()->first()->quantity);
    }
}
