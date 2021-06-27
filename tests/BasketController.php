<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Ctrlc\Basket\Contracts\Cart;
use Ctrlc\Basket\Contracts\ProductVariantContract;
use Illuminate\Routing\Controller;

class BasketController extends Controller
{
    public function getBasket(Cart $cart)
    {
        return response()->json($cart->toJson());
    }

    public function add(Cart $cart, ProductVariantContract $variant)
    {
        try {
            $cart->add($variant);

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function remove(Cart $cart, ProductVariantContract $variant)
    {
        try {
            $cart->remove($variant);

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
