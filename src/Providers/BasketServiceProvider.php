<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Providers;

use Ctrlc\Basket\Models\BasketItem;
use Ctrlc\Basket\Observers\BasketItemObserver;
use Ctrlc\Basket\Services\BasketService;
use Illuminate\Support\ServiceProvider;

class BasketServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__, 2).'/config/config.php', 'ctrlc.basket');
        $this->app->singleton('basket', fn () => new BasketService());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            dirname(__DIR__, 2).'/database/migrations/2020_01_11_125853_create_baskets.php',
            dirname(__DIR__, 2).'/database/migrations/2020_01_11_125853_create_products_variants_table.php',
        ]);

        BasketItem::observe(BasketItemObserver::class);
    }
}
