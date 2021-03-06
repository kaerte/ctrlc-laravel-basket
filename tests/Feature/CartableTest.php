<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\Cart;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartableTest extends TestCase
{
    use RefreshDatabase;

    public Model $cartable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartable = User::factory()->create();
    }

    public function test_cartable_has_empty_cart(): void
    {
        self::assertEmpty($this->cartable->cart);
    }

    public function test_assign_cart_to_cartable(): void
    {
        $cart = $this->app->make(Cart::class);
        $this->cartable->cart()->save($cart);

        self::assertTrue($this->cartable->is($this->cartable->cart->cartable));
    }
}
