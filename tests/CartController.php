<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests;

use Ctrlc\Cart\Contracts\Cart;
use Ctrlc\Cart\Contracts\ProductVariantContract;
use Illuminate\Routing\Controller;

class CartController extends Controller
{
    public function getCart(Cart $cart)
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
