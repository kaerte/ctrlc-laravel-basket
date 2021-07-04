<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\Facades\Cart;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCartTest extends TestCase
{
    use RefreshDatabase;

    public User $productable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productable = User::factory()
            ->hasVariants(1, [
                'default' => 1,
            ])
            ->create();
    }

    public function test_api_get_empty_cart(): void
    {
        $request = $this->get(route('api_test.cart.get'));
        $request->assertJsonStructure([
            'id',
            'total',
            'items',
            'discount_code',
            'discounted_amount',
        ]);
    }

    public function test_api_get_cart(): void
    {
        Cart::add($this->productable->defaultVariant);

        $request = $this->get(route('api_test.cart.get'));
        $request->assertJsonStructure([
            'id',
            'total',
            'items' => [
                0 => [
                    'id',
                    'quantity',
                    'price',
                    'name',
                    'meta',
                ],
            ],
            'discount_code',
            'discounted_amount',
        ]);
    }
}
