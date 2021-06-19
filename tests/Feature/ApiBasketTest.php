<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Facades\Basket;
use Ctrlc\Basket\Tests\TestCase;
use Ctrlc\Basket\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiBasketTest extends TestCase
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

    public function test_api_get_empty_basket(): void
    {
        $request = $this->get(route('api.basket.get'));
        $request->assertJsonStructure([
            'id',
            'total',
            'items',
        ]);
    }

    public function test_api_get_basket(): void
    {
        Basket::add($this->productable->defaultVariant);

        $request = $this->get(route('api.basket.get'));
        $request->assertJsonStructure([
            'id',
            'total',
            'items' => [
                0 => [
                    'id',
                    'quantity',
                    'price',
                    'name',
                    'metadata',
                ],
            ],
        ]);
    }
}
