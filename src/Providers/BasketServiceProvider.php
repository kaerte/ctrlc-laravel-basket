<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Providers;

use Ctrlc\Basket\Contracts\Cart;
use Ctrlc\Basket\Models\Basket;
use Ctrlc\Basket\Models\BasketItem;
use Ctrlc\Basket\Observers\BasketItemObserver;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class BasketServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__, 2).'/config/config.php', 'ctrlc.basket');
        $this->app->singleton(Cart::class, function () {
            $cartId = Session::get('cart_id', null);

            if ($cartId) {
                return Basket::find($cartId);
            }

            $newCart = (new Basket())->create();
            Session::put('cart_id', $newCart->id);

            return $newCart;
        });
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
