<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests\Feature;

use Ctrlc\Cart\CartItemable;
use Ctrlc\Cart\Tests\TestCase;
use Ctrlc\Cart\Tests\User as Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAndVariantsTest extends TestCase
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

    public function test_product_creation(): void
    {
        self::assertInstanceOf(Product::class, $this->productVariant->productable);
    }
}
