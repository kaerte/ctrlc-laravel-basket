<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\Contracts\ProductVariantContract;
use Ctrlc\Cart\Facades\Cart;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User;
use Ctrlc\DiscountCode\Models\DiscountCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartDiscountCodeTest extends TestCase
{
    use RefreshDatabase;

    public Model $productable;

    public ProductVariantContract $productVariant;

    private int $variantQuantity = 1;
    
    public const INITIAL_TOTAL = 111;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productable = User::factory()
            ->hasVariants(1, [
                'default' => 1,
                'quantity' => $this->variantQuantity,
                'price' => self::INITIAL_TOTAL,
            ])
            ->create();

        $this->productVariant = $this->productable->defaultVariant;
    }

    public function test_cart_has_attributes(): void
    {
        $cart = Cart::get()->toArray();

        self::assertArrayHasKey('total', $cart);
        self::assertArrayHasKey('discount_code', $cart);
        self::assertArrayHasKey('discounted_amount', $cart);
    }
    
    public function test_apply_20_money_off_code(): void
    {
        $value = 20;
        $discountCode = DiscountCode::factory()->money()->active()->create(['value' => $value]);
        Cart::add($this->productVariant)
            ->addDiscountCode($discountCode);
        
        self::assertEquals(self::INITIAL_TOTAL - $value, Cart::total());
    }

    public function test_apply_50_off_percent_code(): void
    {
        $value = 11;
        $discountCode = DiscountCode::factory()->percent()->active()->create(['value' => $value]);
        Cart::add($this->productVariant)
            ->addDiscountCode($discountCode);

        $percent = $value/100;
        $expected = (int) (self::INITIAL_TOTAL - round(self::INITIAL_TOTAL * $percent));
        self::assertEquals($expected, Cart::total());
        self::assertEquals($expected, Cart::get()->total);
    }

    public function test_clear_discount_code(): void
    {
        $value = 10;
        $discountCode = DiscountCode::factory()->percent()->active()->create(['value' => $value]);
        Cart::add($this->productVariant)
            ->addDiscountCode($discountCode)
            ->removeDiscountCode();

        self::assertEquals(self::INITIAL_TOTAL, Cart::total());
    }

    public function test_money_code_cannot_make_total_negative(): void
    {
        $value = 500;
        $discountCode = DiscountCode::factory()->money()->active()->create(['value' => $value]);
        Cart::add($this->productVariant)
            ->addDiscountCode($discountCode);

        self::assertEquals(0, Cart::total());
    }

    public function test_percent_code_cannot_make_total_negative(): void
    {
        $value = 500;
        $discountCode = DiscountCode::factory()->percent()->active()->create(['value' => $value]);
        Cart::add($this->productVariant)
            ->addDiscountCode($discountCode);

        self::assertEquals(0, Cart::total());
    }
}
