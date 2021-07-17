<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Providers;

use Ctrlc\Cart\Cart;
use Ctrlc\Cart\CartItem;
use Ctrlc\Cart\EloquentCart;
use Ctrlc\Cart\EloquentCartItem;
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

        
        $this->app->bind(Cart::class, function () {
            $cartId = Cache::get('ctrlc:cart_id', null);
            $cart = EloquentCart::find($cartId);
            
            if ($cart) {
                return $cart;
            }

            $newCart = (new EloquentCart())->create();
            Cache::forever('ctrlc:cart_id', $newCart->id);

            return $newCart;
        });

        $this->app->bind(CartItem::class, EloquentCartItem::class);
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
    }
}
