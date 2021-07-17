<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Tests;

use Ctrlc\Cart\Cart;
use Ctrlc\Cart\CartItemable;
use Ctrlc\DiscountCode\Models\DiscountCode;
use Ctrlc\DiscountCode\Rules\DiscountCodeRule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CartController extends Controller
{
    public function getCart(Cart $cart)
    {
        return response()->json($cart->toJson());
    }

    public function add(Cart $cart, CartItemable $variant)
    {
        try {
            $cart->add($variant);

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function remove(Cart $cart, CartItemable $variant)
    {
        try {
            $cart->remove($variant);

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function addDiscountCode(Request $request, Cart $cart)
    {
        $request->validate([
            'discount_code' => ['exists:discount_codes,code', new DiscountCodeRule],
        ]);

        $discountCode = DiscountCode::where('code', $request->input('discount_code'))->first();

        try {
            $cart->discountCode()->associate($discountCode);

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function removeDiscountCode(Cart $cart)
    {
        try {
            $cart->discountCode()->disassociate();
            $cart->save();

            return response()->json($cart->toJson());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
