<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Contracts\Cart;
use Ctrlc\Basket\Tests\TestCase;
use Ctrlc\Basket\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

class BasketableTest extends TestCase
{
    use RefreshDatabase;

    public User $basketable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basketable = User::factory()->create();
    }

    public function test_basketable_has_empty_basket(): void
    {
        self::assertEmpty($this->basketable->basket);
    }

    public function test_assign_basket_to_basketable(): void
    {
        $basket = App::make(Cart::class);
        $this->basketable->basket()->save($basket);

        self::assertTrue($this->basketable->is($this->basketable->basket->basketable));
    }
}
