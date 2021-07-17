<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests;

use Ctrlc\Cart\Cart;
use Ctrlc\Cart\CartItemable;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CartController extends Controller
{
    public function getCart(Cart $cart): JsonResponse
    {
        return response()->json($cart->toJson());
    }

    public function add(Cart $cart, CartItemable $variant): JsonResponse
    {
        try {
            $cart->add($variant);

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
