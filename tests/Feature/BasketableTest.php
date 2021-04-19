<?php declare(strict_types=1);

namespace Ctrlc\Basket\Tests\Feature;

use Ctrlc\Basket\Facades\Basket;
use Ctrlc\Basket\Tests\TestCase;
use Ctrlc\Basket\Tests\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $basketable = Basket::getFacadeRoot();

        $this->basketable->basket()->save($basketable);

        self::assertTrue($this->basketable->is($this->basketable->basket->basketable));
    }
}
