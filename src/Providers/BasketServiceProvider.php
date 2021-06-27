<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Providers;

use Ctrlc\Basket\Contracts\Cart as CartContract;
use Ctrlc\Basket\Models\Cart;
use Ctrlc\Basket\Models\CartItem;
use Ctrlc\Basket\Observers\CartItemObserver;
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
        $this->app->singleton(CartContract::class, function () {
            $cartId = Session::get('cart_id', null);

            if ($cartId) {
                return Cart::find($cartId);
            }

            $newCart = (new Cart())->create();
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

        CartItem::observe(CartItemObserver::class);
    }
}
