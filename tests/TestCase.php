<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests;

use Ctrlc\Cart\Providers\CartServiceProvider;
use Ctrlc\DiscountCode\Providers\DiscountCodeServiceProvider;
use Illuminate\Routing\Router;
use Plank\Metable\MetableServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [CartServiceProvider::class, MetableServiceProvider::class, DiscountCodeServiceProvider::class];
    }

    protected function defineRoutes($router): void
    {
        /** @var $router Router */
        $router->get('/cart/get', [CartController::class, 'getCart'])->name('api_test.cart.get');
    }
}
