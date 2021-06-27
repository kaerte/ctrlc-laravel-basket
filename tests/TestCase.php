<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Ctrlc\Basket\Providers\BasketServiceProvider;
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
        return [BasketServiceProvider::class, MetableServiceProvider::class];
    }

    protected function defineRoutes($router): void
    {
        /** @var $router Router */
        $router->get('/basket/get', [BasketController::class, 'getBasket'])->name('api_test.basket.get');
        $router->post('/basket/add', [BasketController::class, 'add'])->name('api_test.basket.add');
        $router->post('/basket/remove', [BasketController::class, 'remove'])->name('api_test.basket.remove');
    }
}
