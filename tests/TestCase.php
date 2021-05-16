<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Ctrlc\Basket\Providers\BasketServiceProvider;
use Illuminate\Routing\Router;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'../database/migrations');
        $this->loadMigrationsFrom(__DIR__.'../migrations');
    }

    protected function getPackageProviders($app)
    {
        return [BasketServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__.'/migrations/2014_10_12_000000_create_users_table.php';

        (new \CreateUsersTable())->up();
    }

    protected function defineRoutes($router): void
    {
        /** @var $router Router */
        $router->get('/basket/get', [BasketController::class, 'getBasket'])->name('api.basket.get');
        $router->post('/basket/add', [BasketController::class, 'add'])->name('api.basket.add');
        $router->post('/basket/remove', [BasketController::class, 'remove'])->name('api.basket.remove');
    }
}
