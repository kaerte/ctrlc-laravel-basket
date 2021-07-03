<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Providers;

use Ctrlc\Cart\Contracts\Cart as CartContract;
use Ctrlc\Cart\Models\Cart;
use Ctrlc\Cart\Models\CartItem;
use Ctrlc\Cart\Observers\CartItemObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__, 2).'/config/config.php', 'ctrlc.cart');
        $this->app->singleton(CartContract::class, function () {
            $cartId = Cache::get('ctrlc:cart_id', null);

            if ($cartId) {
                return Cart::find($cartId);
            }

            $newCart = (new Cart())->create();
            Cache::forever('ctrlc:cart_id', $newCart->id);

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
            dirname(__DIR__, 2).'/database/migrations/2020_01_11_125853_create_carts.php',
            dirname(__DIR__, 2).'/database/migrations/2020_01_11_125853_create_products_variants_table.php',
        ]);

        CartItem::observe(CartItemObserver::class);
    }
}
