<?php declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Models\Product;
use Ctrlc\Basket\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_api_get_product(): void
    {
        $product = Product::factory()
            ->hasVariants(1, [
                'default' => 1,
            ])
            ->create();

        $request = $this->get(route('products.show', $product));
        $request->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);
    }

    public function test_api_get_products(): void
    {
        Product::factory(10)
            ->hasVariants(1, [
                'default' => 1,
            ])
            ->create();
        $request = $this->get(route('products.index'));

        $request->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'name',
                ],
            ],
        ]);
    }
}
